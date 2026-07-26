<?php

use App\Http\Controllers\PayslipPdfController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('components.layouts.app');
});

// PDF Download Routes
Route::get('/pdf/payslip/{payslip}', [PayslipPdfController::class, 'downloadPayslip'])->name('pdf.payslip');
Route::get('/pdf/employee-report/{employee}', [PayslipPdfController::class, 'downloadEmployeeReport'])->name('pdf.employee-report');
