<?php

namespace App\Livewire;

use App\Models\Employee;
use App\Models\Payslip;
use App\Models\TaxLiability;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    // Selected payslip view state
    public $selectedEmployeeId = null;
    public $selectedPayslipId = null;

    // Listeners to refresh data when updates occur
    protected $listeners = [
        'employee-updated' => '$refresh',
        'payslip-created' => '$refresh',
    ];

    public function render()
    {
        // 1. Calculations for KPIs
        $totalEmployees = Employee::count();
        $activeEmployees = Employee::where('active', true)->count();
        $totalGrossPaid = Payslip::sum('gross_pay');
        $totalNetPaid = Payslip::sum('net_pay');
        
        $totalPayeLiabilities = Payslip::sum('paye');
        $totalUscLiabilities = Payslip::sum('usc');
        $totalPrsiLiabilities = Payslip::sum('prsi') + Payslip::sum('employer_prsi');
        $totalLiability = $totalPayeLiabilities + $totalUscLiabilities + $totalPrsiLiabilities;

        // 2. Department distribution data
        $departmentData = Employee::select('department', DB::raw('count(*) as count'))
            ->groupBy('department')
            ->get();

        // 3. Wage distributions for charts
        $wageDist = Payslip::select('gross_pay')->get();

        // 4. Payslip Historical Auditing Segment (Previous, Current, Accumulated)
        $currentPayslip = null;
        $previousPayslip = null;
        $accumulated = [
            'gross_pay' => 0.00,
            'paye' => 0.00,
            'usc' => 0.00,
            'prsi' => 0.00,
            'employer_prsi' => 0.00,
            'net_pay' => 0.00,
        ];

        $employeePayslips = collect();

        if ($this->selectedEmployeeId) {
            $employeePayslips = Payslip::where('employee_id', $this->selectedEmployeeId)
                ->orderBy('period_end', 'desc')
                ->get();

            if ($this->selectedPayslipId) {
                $currentPayslip = Payslip::find($this->selectedPayslipId);
            } elseif ($employeePayslips->isNotEmpty()) {
                $currentPayslip = $employeePayslips->first();
                $this->selectedPayslipId = $currentPayslip->id;
            }

            if ($currentPayslip) {
                // Fetch Previous Payslip
                $previousPayslip = Payslip::where('employee_id', $this->selectedEmployeeId)
                    ->where('period_end', '<', $currentPayslip->period_start)
                    ->orderBy('period_end', 'desc')
                    ->first();

                // Fetch Accumulated Year-to-Date up to current payslip
                $year = date('Y', strtotime($currentPayslip->period_end));
                $accumulatedQuery = Payslip::where('employee_id', $this->selectedEmployeeId)
                    ->whereYear('period_end', $year)
                    ->where('period_end', '<=', $currentPayslip->period_end)
                    ->get();

                $accumulated = [
                    'gross_pay' => $accumulatedQuery->sum('gross_pay'),
                    'paye' => $accumulatedQuery->sum('paye'),
                    'usc' => $accumulatedQuery->sum('usc'),
                    'prsi' => $accumulatedQuery->sum('prsi'),
                    'employer_prsi' => $accumulatedQuery->sum('employer_prsi'),
                    'net_pay' => $accumulatedQuery->sum('net_pay'),
                ];
            }
        }

        return view('livewire.dashboard', [
            'totalEmployees' => $totalEmployees,
            'activeEmployees' => $activeEmployees,
            'totalGrossPaid' => $totalGrossPaid,
            'totalNetPaid' => $totalNetPaid,
            'totalLiability' => $totalLiability,
            'totalPayeLiabilities' => $totalPayeLiabilities,
            'totalUscLiabilities' => $totalUscLiabilities,
            'totalPrsiLiabilities' => $totalPrsiLiabilities,
            'departmentData' => $departmentData,
            'employees' => Employee::all(),
            'employeePayslips' => $employeePayslips,
            'currentPayslip' => $currentPayslip,
            'previousPayslip' => $previousPayslip,
            'accumulated' => $accumulated,
        ]);
    }
}
