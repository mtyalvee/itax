<?php

namespace App\Livewire;

use App\Models\Employee;
use App\Models\Payslip;
use App\Models\TaxLiability;
use App\Services\PayrollCalculatorService;
use Livewire\Component;

class TaxCalculator extends Component
{
    // Inputs
    public $selectedEmployeeId = null;
    public $hoursWorked = 37.5;
    public $overtimeHours = 0;
    public $bonus = 0.00;
    public $periodStart;
    public $periodEnd;

    // Calculation results
    public $calculation = null;
    public $lastSavedPayslipId = null;

    protected $rules = [
        'selectedEmployeeId' => 'required|exists:employees,id',
        'hoursWorked' => 'required|numeric|min:0',
        'overtimeHours' => 'required|numeric|min:0',
        'bonus' => 'required|numeric|min:0',
        'periodStart' => 'required|date',
        'periodEnd' => 'required|date|after_or_equal:periodStart',
    ];

    public function mount()
    {
        $this->periodStart = now()->startOfWeek()->format('Y-m-d');
        $this->periodEnd = now()->endOfWeek()->format('Y-m-d');
    }

    public function updated($propertyName)
    {
        // Whenever inputs update, run the calculation if employee is selected
        if (in_array($propertyName, ['selectedEmployeeId', 'hoursWorked', 'overtimeHours', 'bonus'])) {
            $this->runCalculation();
        }
    }

    public function runCalculation()
    {
        if (!$this->selectedEmployeeId) {
            $this->calculation = null;
            return;
        }

        $employee = Employee::find($this->selectedEmployeeId);
        if (!$employee) {
            $this->calculation = null;
            return;
        }

        // Calculate Gross Pay:
        // Basic: if hourly_rate > 0, basic = hourly_rate * hoursWorked. Otherwise, weekly share of annual salary: salary / 52.
        $basicPay = 0.0;
        if (floatval($employee->hourly_rate) > 0) {
            $basicPay = floatval($employee->hourly_rate) * floatval($this->hoursWorked);
        } else {
            $basicPay = floatval($employee->salary) / 52.0;
        }

        // Overtime: 1.5x of hourly rate. If hourly rate is 0, we can calculate standard rate = salary / 52 / 37.5
        $hourlyRateForOvertime = floatval($employee->hourly_rate) > 0
            ? floatval($employee->hourly_rate)
            : (floatval($employee->salary) / 52.0 / 37.5);

        $overtimePay = floatval($this->overtimeHours) * ($hourlyRateForOvertime * 1.5);
        $bonusPay = floatval($this->bonus);

        $totalGross = $basicPay + $overtimePay + $bonusPay;

        // Run tax computation using the service
        $calculator = new PayrollCalculatorService();
        $this->calculation = $calculator->calculateWeekly(
            $totalGross,
            floatval($employee->tax_credit),
            floatval($employee->cutoff_point)
        );

        // Add additional detail metadata for display steps
        $this->calculation['basic_pay'] = round($basicPay, 2);
        $this->calculation['overtime_pay'] = round($overtimePay, 2);
        $this->calculation['bonus_pay'] = $bonusPay;
        $this->calculation['hourly_rate_overtime'] = round($hourlyRateForOvertime * 1.5, 2);
    }

    public function savePayslip()
    {
        $this->validate();
        
        $this->runCalculation();

        if (!$this->calculation) {
            return;
        }

        // Create or update payslip for the selected employee in the given period
        $payslip = Payslip::updateOrCreate(
            [
                'employee_id' => $this->selectedEmployeeId,
                'period_start' => $this->periodStart,
                'period_end' => $this->periodEnd,
            ],
            [
                'gross_pay' => $this->calculation['gross_pay'],
                'paye' => $this->calculation['paye'],
                'usc' => $this->calculation['usc'],
                'prsi' => $this->calculation['prsi'],
                'employer_prsi' => $this->calculation['employer_prsi'],
                'net_pay' => $this->calculation['net_pay'],
                'hours_worked' => $this->hoursWorked,
                'overtime_hours' => $this->overtimeHours,
                'bonus' => $this->bonus,
                'status' => 'processed',
            ]
        );

        // Record or update Monthly Tax Liability
        $taxPeriod = date('Y-m', strtotime($this->periodStart));
        
        // Recalculate total liability for this tax period from all processed payslips
        $allPayslipsInPeriod = Payslip::where('period_start', '>=', date('Y-m-01', strtotime($this->periodStart)))
            ->where('period_end', '<=', date('Y-m-t', strtotime($this->periodStart)))
            ->get();

        $totalPaye = $allPayslipsInPeriod->sum('paye');
        $totalUsc = $allPayslipsInPeriod->sum('usc');
        $totalPrsiEmployee = $allPayslipsInPeriod->sum('prsi');
        $totalPrsiEmployer = $allPayslipsInPeriod->sum('employer_prsi');
        $totalLiability = $totalPaye + $totalUsc + $totalPrsiEmployee + $totalPrsiEmployer;

        TaxLiability::updateOrCreate(
            ['tax_period' => $taxPeriod],
            [
                'paye' => $totalPaye,
                'usc' => $totalUsc,
                'prsi_employee' => $totalPrsiEmployee,
                'prsi_employer' => $totalPrsiEmployer,
                'total_liability' => $totalLiability,
            ]
        );

        $this->lastSavedPayslipId = $payslip->id;
        session()->flash('message', 'Payslip processed successfully and liability ledger updated.');
        $this->dispatch('payslip-created');
    }

    public function render()
    {
        return view('livewire.tax-calculator', [
            'employees' => Employee::where('active', true)->get()
        ]);
    }
}
