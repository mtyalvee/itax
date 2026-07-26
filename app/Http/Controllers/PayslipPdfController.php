<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Payslip;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PayslipPdfController extends Controller
{
    /**
     * Download a single payslip as a PDF document.
     */
    public function downloadPayslip(Payslip $payslip)
    {
        $payslip->load('employee');

        $pdf = Pdf::loadView('pdf.payslip', [
            'payslip' => $payslip,
            'employee' => $payslip->employee,
        ]);

        $filename = 'Payslip_' . str_replace(' ', '_', $payslip->employee->name) . '_' . $payslip->period_start->format('Ymd') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Download a full employee report as a PDF document.
     */
    public function downloadEmployeeReport(Employee $employee)
    {
        $payslips = $employee->payslips()->orderBy('period_end', 'desc')->get();

        // Calculate YTD accumulated totals
        $currentYear = now()->year;
        $ytdPayslips = $employee->payslips()
            ->whereYear('period_end', $currentYear)
            ->get();

        $accumulated = [
            'gross_pay' => $ytdPayslips->sum('gross_pay'),
            'paye' => $ytdPayslips->sum('paye'),
            'usc' => $ytdPayslips->sum('usc'),
            'prsi' => $ytdPayslips->sum('prsi'),
            'employer_prsi' => $ytdPayslips->sum('employer_prsi'),
            'net_pay' => $ytdPayslips->sum('net_pay'),
        ];

        $pdf = Pdf::loadView('pdf.employee-report', [
            'employee' => $employee,
            'payslips' => $payslips,
            'accumulated' => $accumulated,
            'year' => $currentYear,
        ]);

        $filename = 'Employee_Report_' . str_replace(' ', '_', $employee->name) . '_' . now()->format('Ymd') . '.pdf';

        return $pdf->download($filename);
    }
}
