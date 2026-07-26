<?php

namespace App\Services;

class PayrollCalculatorService
{
    /**
     * Calculate Irish Payroll taxes and net pay for a weekly period.
     * All thresholds are based on a weekly breakdown of standard Irish tax rules.
     *
     * @param float $grossPay Total gross pay for the week
     * @param float $annualTaxCredit Annual tax credit (default €3,750)
     * @param float $annualCutoffPoint Annual standard rate cutoff point (default €42,000)
     * @return array Containing break down of gross, PAYE, USC, PRSI (employee & employer), and net pay
     */
    public function calculateWeekly(float $grossPay, float $annualTaxCredit = 3750.00, float $annualCutoffPoint = 42000.00): array
    {
        // 1. Calculate PAYE
        $weeklyCutoff = $annualCutoffPoint / 52.0;
        $weeklyTaxCredit = $annualTaxCredit / 52.0;

        $payeAt20 = min($grossPay, $weeklyCutoff) * 0.20;
        $payeAt40 = max(0.0, $grossPay - $weeklyCutoff) * 0.40;
        $grossPAYE = $payeAt20 + $payeAt40;
        $netPAYE = max(0.0, $grossPAYE - $weeklyTaxCredit);

        // 2. Calculate USC (Universal Social Charge) - Standard 2026 rates/thresholds
        // Annual limits: €12,012 (0.5%), next €13,748 (2%), next €44,344 (4%), balance (8%)
        $uscBand1Limit = 12012.00 / 52.0; // ~€231.00
        $uscBand2Limit = 13748.00 / 52.0; // ~€264.38 (cumulative limit: €495.38)
        $uscBand3Limit = 44344.00 / 52.0; // ~€852.77 (cumulative limit: €1348.15)

        $usc = 0.0;
        $remainingPay = $grossPay;

        // Band 1: 0.5%
        $taxableBand1 = min($remainingPay, $uscBand1Limit);
        $usc += $taxableBand1 * 0.005;
        $remainingPay = max(0.0, $remainingPay - $taxableBand1);

        // Band 2: 2.0%
        $taxableBand2 = min($remainingPay, $uscBand2Limit);
        $usc += $taxableBand2 * 0.02;
        $remainingPay = max(0.0, $remainingPay - $taxableBand2);

        // Band 3: 4.0%
        $taxableBand3 = min($remainingPay, $uscBand3Limit);
        $usc += $taxableBand3 * 0.04;
        $remainingPay = max(0.0, $remainingPay - $taxableBand3);

        // Band 4: 8.0%
        if ($remainingPay > 0.0) {
            $usc += $remainingPay * 0.08;
        }

        // 3. Calculate PRSI (Pay Related Social Insurance - Class A)
        // Employee PRSI: 4% of gross pay if weekly gross pay > €352.00
        $employeePRSI = 0.0;
        if ($grossPay > 352.00) {
            // Apply standard Class A1 4% rate
            // Note: Ireland has a PRSI credit for earnings between €352.01 and €424.
            // Let's implement the PRSI credit to be exact:
            // Max credit €12 per week. Reduced by 1/6 of gross earnings over €352.
            $basePrsi = $grossPay * 0.04;
            if ($grossPay <= 424.00) {
                $reduction = ($grossPay - 352.00) / 6.0;
                $credit = max(0.0, 12.00 - $reduction);
                $employeePRSI = max(0.0, $basePrsi - $credit);
            } else {
                $employeePRSI = $basePrsi;
            }
        }

        // Employer PRSI: Class A
        // 8.8% on all earnings up to €441.00 per week
        // 11.05% on all earnings if weekly gross pay > €441.00 per week
        $employerPRSI = 0.0;
        if ($grossPay > 0.0) {
            if ($grossPay <= 441.00) {
                $employerPRSI = $grossPay * 0.088;
            } else {
                $employerPRSI = $grossPay * 0.1105;
            }
        }

        // 4. Rounded Deductions & Net Wages
        $roundedGross = round($grossPay, 2);
        $roundedPaye = round($netPAYE, 2);
        $roundedUsc = round($usc, 2);
        $roundedPrsi = round($employeePRSI, 2);
        $roundedEmployerPrsi = round($employerPRSI, 2);

        $netWages = $roundedGross - $roundedPaye - $roundedUsc - $roundedPrsi;

        return [
            'gross_pay' => $roundedGross,
            'paye' => $roundedPaye,
            'usc' => $roundedUsc,
            'prsi' => $roundedPrsi,
            'employer_prsi' => $roundedEmployerPrsi,
            'net_pay' => round($netWages, 2),
            'meta' => [
                'paye_gross' => round($grossPAYE, 2),
                'paye_credit' => round($weeklyTaxCredit, 2),
                'paye_cutoff' => round($weeklyCutoff, 2),
            ]
        ];
    }
}
