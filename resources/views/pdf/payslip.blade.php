<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Payslip - {{ $employee->name }}</title>
    <style>
        body { font-family: Helvetica, Arial, sans-serif; font-size: 11px; color: #333; margin: 0; padding: 20px 30px; }
        h1 { font-size: 18px; margin: 0 0 2px 0; }
        h2 { font-size: 12px; font-weight: bold; margin: 18px 0 6px 0; padding-bottom: 3px; border-bottom: 1px solid #ccc; text-transform: uppercase; letter-spacing: 0.5px; }
        .sub { font-size: 9px; color: #888; margin-bottom: 15px; }
        .row { display: table; width: 100%; margin-bottom: 12px; }
        .col { display: table-cell; width: 50%; vertical-align: top; }
        .field { margin-bottom: 6px; }
        .label { font-size: 9px; color: #888; text-transform: uppercase; }
        .value { font-size: 11px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 4px; }
        th { text-align: left; font-size: 9px; text-transform: uppercase; color: #666; padding: 5px 8px; border-bottom: 1px solid #999; }
        th:last-child { text-align: right; }
        td { padding: 5px 8px; border-bottom: 1px solid #eee; font-size: 11px; }
        td:last-child { text-align: right; font-weight: 600; }
        .total td { border-top: 1px solid #333; border-bottom: none; font-weight: bold; font-size: 12px; }
        .net-box { text-align: center; margin: 16px 0; padding: 12px; border: 2px solid #333; }
        .net-box .lbl { font-size: 9px; text-transform: uppercase; color: #666; }
        .net-box .amt { font-size: 24px; font-weight: bold; }
        .footer { margin-top: 20px; padding-top: 8px; border-top: 1px solid #ccc; font-size: 8px; color: #999; text-align: center; }
    </style>
</head>
<body>
    <h1>PAYSLIP</h1>
    <div class="sub">Confidential &mdash; Generated {{ now()->format('d M Y, H:i') }}</div>

    <div class="row">
        <div class="col">
            <div class="field"><span class="label">Employee</span><br><span class="value">{{ $employee->name }}</span></div>
            <div class="field"><span class="label">PPS Number</span><br><span class="value">{{ $employee->pps_number }}</span></div>
            <div class="field"><span class="label">Department</span><br><span class="value">{{ $employee->department }} &mdash; {{ $employee->job_title }}</span></div>
        </div>
        <div class="col">
            <div class="field"><span class="label">Pay Period</span><br><span class="value">{{ $payslip->period_start->format('d M Y') }} &ndash; {{ $payslip->period_end->format('d M Y') }}</span></div>
            <div class="field"><span class="label">Status</span><br><span class="value">{{ ucfirst($payslip->status) }}</span></div>
            <div class="field"><span class="label">Email</span><br><span class="value">{{ $employee->email }}</span></div>
        </div>
    </div>

    <h2>Earnings</h2>
    <table>
        <thead><tr><th>Description</th><th>Amount (€)</th></tr></thead>
        <tbody>
            <tr>
                <td>Basic Pay{{ $payslip->hours_worked > 0 ? ' (' . $payslip->hours_worked . ' hrs)' : '' }}</td>
                <td>{{ number_format($payslip->gross_pay - $payslip->bonus, 2) }}</td>
            </tr>
            @if($payslip->bonus > 0)
            <tr><td>Bonus / Allowances</td><td>{{ number_format($payslip->bonus, 2) }}</td></tr>
            @endif
            <tr class="total"><td>Gross Pay</td><td>€{{ number_format($payslip->gross_pay, 2) }}</td></tr>
        </tbody>
    </table>

    <h2>Deductions</h2>
    <table>
        <thead><tr><th>Tax / Charge</th><th>Amount (€)</th></tr></thead>
        <tbody>
            <tr><td>PAYE (Income Tax)</td><td>{{ number_format($payslip->paye, 2) }}</td></tr>
            <tr><td>USC (Universal Social Charge)</td><td>{{ number_format($payslip->usc, 2) }}</td></tr>
            <tr><td>PRSI (Employee — Class A)</td><td>{{ number_format($payslip->prsi, 2) }}</td></tr>
            <tr class="total"><td>Total Deductions</td><td>€{{ number_format($payslip->paye + $payslip->usc + $payslip->prsi, 2) }}</td></tr>
        </tbody>
    </table>

    <div class="net-box">
        <div class="lbl">Net Take-Home Pay</div>
        <div class="amt">€{{ number_format($payslip->net_pay, 2) }}</div>
    </div>

    <h2>Employer Contributions</h2>
    <table>
        <thead><tr><th>Description</th><th>Amount (€)</th></tr></thead>
        <tbody>
            <tr><td>Employer PRSI (Class A)</td><td>{{ number_format($payslip->employer_prsi, 2) }}</td></tr>
            <tr class="total"><td>Total Cost to Employer</td><td>€{{ number_format($payslip->gross_pay + $payslip->employer_prsi, 2) }}</td></tr>
        </tbody>
    </table>

    <div class="footer">
        This is a computer-generated payslip. For queries, contact the Payroll Department.
    </div>
</body>
</html>
