<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Payslip;
use App\Models\TaxLiability;
use App\Services\PayrollCalculatorService;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Employees
        $employees = [
            [
                'name' => 'Sean Higgins',
                'email' => 'sean.higgins@enterprise.ie',
                'pps_number' => '1234567HA',
                'department' => 'Software Development',
                'job_title' => 'Senior Architect',
                'hourly_rate' => 0.00,
                'salary' => 85000.00,
                'active' => true,
                'tax_credit' => 3750.00,
                'cutoff_point' => 42000.00,
            ],
            [
                'name' => 'Niamh Kelly',
                'email' => 'niamh.kelly@enterprise.ie',
                'pps_number' => '9876543KB',
                'department' => 'HR & Operations',
                'job_title' => 'HR Director',
                'hourly_rate' => 0.00,
                'salary' => 52000.00,
                'active' => true,
                'tax_credit' => 3750.00,
                'cutoff_point' => 42000.00,
            ],
            [
                'name' => 'Liam Murphy',
                'email' => 'liam.murphy@enterprise.ie',
                'pps_number' => '7654321MA',
                'department' => 'Customer Success',
                'job_title' => 'Support Engineer',
                'hourly_rate' => 24.50,
                'salary' => 0.00,
                'active' => true,
                'tax_credit' => 3750.00,
                'cutoff_point' => 42000.00,
            ],
            [
                'name' => 'Aoife Boyle',
                'email' => 'aoife.boyle@enterprise.ie',
                'pps_number' => '4567890BA',
                'department' => 'Marketing',
                'job_title' => 'Growth Manager',
                'hourly_rate' => 0.00,
                'salary' => 42000.00,
                'active' => true,
                'tax_credit' => 3750.00,
                'cutoff_point' => 42000.00,
            ],
        ];

        $calculator = new PayrollCalculatorService();
        $seededEmployees = [];

        foreach ($employees as $empData) {
            $seededEmployees[] = Employee::create($empData);
        }

        // 2. Seed Payslips for the last 3 weeks to show timeline and charts
        $weeks = [
            [
                'start' => date('Y-m-d', strtotime('monday last week - 2 weeks')),
                'end' => date('Y-m-d', strtotime('sunday last week - 2 weeks')),
            ],
            [
                'start' => date('Y-m-d', strtotime('monday last week - 1 week')),
                'end' => date('Y-m-d', strtotime('sunday last week - 1 week')),
            ],
            [
                'start' => date('Y-m-d', strtotime('monday last week')),
                'end' => date('Y-m-d', strtotime('sunday last week')),
            ],
        ];

        foreach ($seededEmployees as $emp) {
            foreach ($weeks as $week) {
                // Calculate gross
                if ($emp->hourly_rate > 0) {
                    $hours = 37.5;
                    $gross = $emp->hourly_rate * $hours;
                } else {
                    $hours = 0.0;
                    $gross = $emp->salary / 52.0;
                }

                // Add some overtime and bonus variation
                $overtime = 0.0;
                $bonus = 0.0;
                if ($emp->name === 'Sean Higgins' && $week['start'] === $weeks[2]['start']) {
                    $bonus = 250.00;
                }
                if ($emp->name === 'Liam Murphy') {
                    $overtime = 5.0; // 5 hours overtime
                    $hourlyRateForOvertime = $emp->hourly_rate;
                    $gross += $overtime * ($hourlyRateForOvertime * 1.5);
                }

                $gross += $bonus;

                $calc = $calculator->calculateWeekly($gross, $emp->tax_credit, $emp->cutoff_point);

                Payslip::create([
                    'employee_id' => $emp->id,
                    'gross_pay' => $calc['gross_pay'],
                    'paye' => $calc['paye'],
                    'usc' => $calc['usc'],
                    'prsi' => $calc['prsi'],
                    'employer_prsi' => $calc['employer_prsi'],
                    'net_pay' => $calc['net_pay'],
                    'hours_worked' => $hours,
                    'overtime_hours' => $overtime,
                    'bonus' => $bonus,
                    'period_start' => $week['start'],
                    'period_end' => $week['end'],
                    'status' => 'processed',
                ]);
            }
        }

        // 3. Compile Monthly Tax Liabilities for July 2026
        $allPayslips = Payslip::all();
        $taxPeriod = date('Y-m', strtotime('monday last week'));
        
        $totalPaye = $allPayslips->sum('paye');
        $totalUsc = $allPayslips->sum('usc');
        $totalPrsiEmployee = $allPayslips->sum('prsi');
        $totalPrsiEmployer = $allPayslips->sum('employer_prsi');
        $totalLiability = $totalPaye + $totalUsc + $totalPrsiEmployee + $totalPrsiEmployer;

        TaxLiability::create([
            'tax_period' => $taxPeriod,
            'paye' => $totalPaye,
            'usc' => $totalUsc,
            'prsi_employee' => $totalPrsiEmployee,
            'prsi_employer' => $totalPrsiEmployer,
            'total_liability' => $totalLiability,
        ]);
    }
}
