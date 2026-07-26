<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\PayrollCalculatorService;

class PayrollCalculatorServiceTest extends TestCase
{
    public function test_weekly_payroll_calculation_basic_salary()
    {
        $calculator = new PayrollCalculatorService();

        // Let's test a typical worker earning €800 per week (~€41,600 / year)
        // With standard €3,750 Tax Credit (~€72.11 / week) and €42,000 Cutoff (~€807.69 / week)
        $result = $calculator->calculateWeekly(800.00, 3750.00, 42000.00);

        // PAYE: €800 <= €807.69. Gross PAYE = 800 * 0.20 = €160.00
        // Net PAYE = 160.00 - 72.115 = €87.88
        $this->assertEquals(800.00, $result['gross_pay']);
        $this->assertEquals(87.88, $result['paye']);

        // USC Band 1 limit: 12012/52 = 231.00 @ 0.5% = 1.155
        // USC Band 2 limit: 13748/52 = 264.38 @ 2.0% = 5.2876
        // USC Band 3: 800 - 231 - 264.38 = 304.62 @ 4.0% = 12.1848
        // Total USC = 1.155 + 5.2876 + 12.1848 = 18.63
        $this->assertEquals(18.63, $result['usc']);

        // PRSI: 800 > 352.00, Class A1 4% = €32.00 (PRSI credit is 0 because 800 > 424)
        $this->assertEquals(32.00, $result['prsi']);

        // Employer PRSI: 800 > 441, Class A1 11.05% = €88.40
        $this->assertEquals(88.40, $result['employer_prsi']);

        // Net Pay = 800 - 87.88 - 18.63 - 32.00 = 661.49
        $this->assertEquals(661.49, $result['net_pay']);
    }

    public function test_weekly_payroll_calculation_low_salary()
    {
        $calculator = new PayrollCalculatorService();

        // Low salary test: €300 per week
        // PAYE: €300 * 0.20 = €60.00. Tax credit €72.115. Net PAYE = 0.
        // USC: Band 1 limit €231 @ 0.5% = 1.155. Band 2: €69 @ 2% = 1.38. Total USC = 2.535 -> 2.54.
        // PRSI: €300 <= €352. Employee PRSI = 0.
        // Employer PRSI: €300 * 8.8% = €26.40.
        // Net: 300 - 0 - 2.54 - 0 = 297.46
        $result = $calculator->calculateWeekly(300.00, 3750.00, 42000.00);

        $this->assertEquals(0.00, $result['paye']);
        $this->assertEquals(2.54, $result['usc']);
        $this->assertEquals(0.00, $result['prsi']);
        $this->assertEquals(26.40, $result['employer_prsi']);
        $this->assertEquals(297.46, $result['net_pay']);
    }
}
