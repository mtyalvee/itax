<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Employee Report - {{ $employee->name }}</title>
    <style>
        body { font-family: Helvetica, Arial, sans-serif; font-size: 10px; color: #333; margin: 0; padding: 20px 30px; }
        h1 { font-size: 18px; margin: 0 0 2px 0; }
        h2 { font-size: 11px; font-weight: bold; margin: 16px 0 6px 0; padding-bottom: 3px; border-bottom: 1px solid #ccc; text-transform: uppercase; letter-spacing: 0.5px; }
        .sub { font-size: 9px; color: #888; margin-bottom: 14px; }
        .row { display: table; width: 100%; margin-bottom: 10px; }
        .col { display: table-cell; width: 33.33%; vertical-align: top; padding-right: 10px; }
        .col:last-child { padding-right: 0; }
        .field { margin-bottom: 5px; }
        .label { font-size: 8px; color: #888; text-transform: uppercase; }
        .value { font-size: 10px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 4px; }
        th { text-align: left; font-size: 8px; text-transform: uppercase; color: #666; padding: 4px 6px; border-bottom: 1px solid #999; }
        td { padding: 4px 6px; border-bottom: 1px solid #eee; font-size: 9px; }
        .num { text-align: right; }
        th.num { text-align: right; }
        td.num { font-weight: 600; }
        .ytd-row { display: table; width: 100%; margin-bottom: 10px; }
        .ytd-cell { display: table-cell; width: 16.66%; text-align: center; padding: 2px; }
        .ytd-box { border: 1px solid #ddd; padding: 6px 4px; }
        .ytd-lbl { font-size: 7px; text-transform: uppercase; color: #888; }
        .ytd-val { font-size: 11px; font-weight: bold; margin-top: 1px; }
        .footer { margin-top: 16px; padding-top: 6px; border-top: 1px solid #ccc; font-size: 8px; color: #999; text-align: center; }
    </style>
</head>
<body>
    <h1>EMPLOYEE REPORT</h1>
    <div class="sub">Year-to-Date Summary &mdash; {{ $year }} &mdash; Generated {{ now()->format('d M Y, H:i') }}</div>

    <h2>Profile</h2>
    <div class="row">
        <div class="col">
            <div class="field"><span class="label">Name</span><br><span class="value">{{ $employee->name }}</span></div>
            <div class="field"><span class="label">Email</span><br><span class="value">{{ $employee->email }}</span></div>
        </div>
        <div class="col">
            <div class="field"><span class="label">PPS Number</span><br><span class="value">{{ $employee->pps_number }}</span></div>
            <div class="field"><span class="label">Department</span><br><span class="value">{{ $employee->department }}</span></div>
        </div>
        <div class="col">
            <div class="field"><span class="label">Job Title</span><br><span class="value">{{ $employee->job_title }}</span></div>
            <div class="field"><span class="label">Status</span><br><span class="value">{{ $employee->active ? 'Active' : 'Inactive' }}</span></div>
        </div>
    </div>

    <h2>Year-to-Date Totals ({{ $year }})</h2>
    <div class="ytd-row">
        <div class="ytd-cell"><div class="ytd-box"><div class="ytd-lbl">Gross</div><div class="ytd-val">€{{ number_format($accumulated['gross_pay'], 2) }}</div></div></div>
        <div class="ytd-cell"><div class="ytd-box"><div class="ytd-lbl">PAYE</div><div class="ytd-val">€{{ number_format($accumulated['paye'], 2) }}</div></div></div>
        <div class="ytd-cell"><div class="ytd-box"><div class="ytd-lbl">USC</div><div class="ytd-val">€{{ number_format($accumulated['usc'], 2) }}</div></div></div>
        <div class="ytd-cell"><div class="ytd-box"><div class="ytd-lbl">PRSI (Emp)</div><div class="ytd-val">€{{ number_format($accumulated['prsi'], 2) }}</div></div></div>
        <div class="ytd-cell"><div class="ytd-box"><div class="ytd-lbl">PRSI (Er)</div><div class="ytd-val">€{{ number_format($accumulated['employer_prsi'], 2) }}</div></div></div>
        <div class="ytd-cell"><div class="ytd-box"><div class="ytd-lbl">Net Pay</div><div class="ytd-val">€{{ number_format($accumulated['net_pay'], 2) }}</div></div></div>
    </div>

    <h2>Payslip History ({{ $payslips->count() }} Records)</h2>
    @if($payslips->isEmpty())
        <p style="color: #999;">No payslips have been processed for this employee.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Period</th>
                    <th>Status</th>
                    <th class="num">Gross (€)</th>
                    <th class="num">PAYE (€)</th>
                    <th class="num">USC (€)</th>
                    <th class="num">PRSI (€)</th>
                    <th class="num">Net Pay (€)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payslips as $payslip)
                <tr>
                    <td>{{ $payslip->period_start->format('d/m') }} – {{ $payslip->period_end->format('d/m/Y') }}</td>
                    <td>{{ ucfirst($payslip->status) }}</td>
                    <td class="num">{{ number_format($payslip->gross_pay, 2) }}</td>
                    <td class="num">{{ number_format($payslip->paye, 2) }}</td>
                    <td class="num">{{ number_format($payslip->usc, 2) }}</td>
                    <td class="num">{{ number_format($payslip->prsi, 2) }}</td>
                    <td class="num">{{ number_format($payslip->net_pay, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        Computer-generated document &mdash; Modern Payroll & Employee Management System
    </div>
</body>
</html>
