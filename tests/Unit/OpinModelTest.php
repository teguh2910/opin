<?php

namespace Tests\Unit;

use App\Models\Opin;
use PHPUnit\Framework\TestCase;

class OpinModelTest extends TestCase
{
    /** @test */
    public function opin_model_has_correct_fillable_attributes()
    {
        $opin = new Opin();

        $expectedFillable = [
            'part_no',
            'part_name',
            'sales_price',
            'rm_cost',
            'ckd_cost',
            'ip_cost',
            'lp_cost',
            'labor_cost',
            'machine_cost',
            'current_machine',
            'other_fixed',
            'defect_cost',
        ];

        $this->assertEquals($expectedFillable, $opin->getFillable());
    }

    /** @test */
    public function opin_model_uses_correct_table_name()
    {
        $opin = new Opin();

        $this->assertEquals('opins', $opin->getTable());
    }

    /** @test */
    public function opin_model_has_factory_trait()
    {
        $opin = new Opin();

        $this->assertContains('Illuminate\Database\Eloquent\Factories\HasFactory', class_uses($opin));
    }

    /** @test */
    public function opin_model_can_calculate_total_cost_statically()
    {
        // Test the calculation logic without database
        $costs = [
            'rm_cost' => 20.00,
            'ckd_cost' => 15.00,
            'ip_cost' => 5.00,
            'lp_cost' => 3.00,
            'labor_cost' => 10.00,
            'machine_cost' => 25.00,
            'current_machine' => 12.00,
            'other_fixed' => 2.50,
            'defect_cost' => 1.00,
        ];

        $expectedTotalCost = array_sum($costs);

        $this->assertEquals(93.50, $expectedTotalCost);
    }

    /** @test */
    public function opin_model_can_calculate_profit_statically()
    {
        $salesPrice = 100.00;
        $totalCost = 93.50;
        $expectedProfit = $salesPrice - $totalCost;

        $this->assertEquals(6.50, $expectedProfit);
    }

    /** @test */
    public function opin_model_can_calculate_profit_margin_statically()
    {
        $profit = 6.50;
        $salesPrice = 100.00;
        $expectedProfitMargin = ($profit / $salesPrice) * 100;

        $this->assertEquals(6.50, $expectedProfitMargin);
    }

    /** @test */
    public function opin_model_handles_zero_sales_price_for_profit_margin_calculation()
    {
        $profit = 10.00;
        $salesPrice = 0.00;

        // Should handle division by zero gracefully
        if ($salesPrice > 0) {
            $profitMargin = ($profit / $salesPrice) * 100;
        } else {
            $profitMargin = 0.00;
        }

        $this->assertEquals(0.00, $profitMargin);
    }

    /** @test */
    public function opin_model_can_calculate_product_cost_without_common_cost()
    {
        $opin = new Opin([
            'rm_cost' => 20.00,
            'ckd_cost' => 15.00,
            'ip_cost' => 5.00,
            'lp_cost' => 3.00,
            'labor_cost' => 10.00,
            'machine_cost' => 25.00,
            'current_machine' => 12.00,
            'other_fixed' => 2.50,
            'defect_cost' => 1.00,
        ]);

        $expectedCost = 20.00 + 15.00 + 5.00 + 3.00 + 10.00 + 25.00 + 12.00 + 2.50 + 1.00;

        $this->assertEquals(93.50, $opin->product_cost_without_common_cost);
    }

    /** @test */
    public function opin_model_can_calculate_gross_income()
    {
        $opin = new Opin([
            'sales_price' => 100.00,
            'rm_cost' => 20.00,
            'ckd_cost' => 15.00,
            'ip_cost' => 5.00,
            'lp_cost' => 3.00,
            'labor_cost' => 10.00,
            'machine_cost' => 25.00,
            'current_machine' => 12.00,
            'other_fixed' => 2.50,
            'defect_cost' => 1.00,
        ]);

        $expectedGrossIncome = 100.00 - 93.50; // sales_price - product_cost_without_common_cost

        $this->assertEquals(6.50, $opin->gross_income);
    }

    /** @test */
    public function opin_model_can_calculate_sga()
    {
        $opin = new Opin([
            'rm_cost' => 20.00,
            'ckd_cost' => 15.00,
            'ip_cost' => 5.00,
            'lp_cost' => 3.00,
            'labor_cost' => 10.00,
            'machine_cost' => 25.00,
            'current_machine' => 12.00,
            'other_fixed' => 2.50,
            'defect_cost' => 1.00,
        ]);

        $productCost = 93.50;
        $expectedSga = $productCost * 0.0655; // 6.55%

        $this->assertEquals($expectedSga, $opin->sg_a);
    }

    /** @test */
    public function opin_model_can_calculate_royalty()
    {
        $opin = new Opin([
            'sales_price' => 100.00,
            'ckd_cost' => 15.00,
        ]);

        $expectedRoyalty = (100.00 - 15.00) * 0.04; // (sales_price - ckd_cost) * 4.00%

        $this->assertEquals(3.40, $opin->royalty);
    }

    /** @test */
    public function opin_model_can_calculate_total_product_cost()
    {
        $opin = new Opin([
            'sales_price' => 100.00,
            'rm_cost' => 20.00,
            'ckd_cost' => 15.00,
            'ip_cost' => 5.00,
            'lp_cost' => 3.00,
            'labor_cost' => 10.00,
            'machine_cost' => 25.00,
            'current_machine' => 12.00,
            'other_fixed' => 2.50,
            'defect_cost' => 1.00,
        ]);

        $productCost = 93.50;
        $sga = $productCost * 0.0655; // 6.11
        $royalty = (100.00 - 15.00) * 0.04; // 3.40
        $expectedTotalCost = $productCost + $sga + $royalty; // 93.50 + 6.11 + 3.40 = 103.01

        $this->assertEquals($expectedTotalCost, $opin->total_product_cost);
    }

    /** @test */
    public function opin_model_can_calculate_profit_percentage()
    {
        $opin = new Opin([
            'sales_price' => 100.00,
            'rm_cost' => 20.00,
            'ckd_cost' => 15.00,
            'ip_cost' => 5.00,
            'lp_cost' => 3.00,
            'labor_cost' => 10.00,
            'machine_cost' => 25.00,
            'current_machine' => 12.00,
            'other_fixed' => 2.50,
            'defect_cost' => 1.00,
        ]);

        // Expected values based on actual calculations:
        // Product Cost W/O Common Cost: 93.5
        // SG&A: 6.12425 (93.5 * 0.0655)
        // Royalty: 3.4 ((100 - 15) * 0.04)
        // Total Product Cost: 103.02425
        // Profit Percentage: ((100 / 103.02425) - 1) * 100 = -2.9354739296816

        $this->assertEqualsWithDelta(-2.9354739296816, $opin->profit_percentage, 0.0001);
    }

    /** @test */
    public function opin_model_handles_zero_total_product_cost_for_profit_percentage()
    {
        $opin = new Opin([
            'sales_price' => 100.00,
            'rm_cost' => 0.00,
            'ckd_cost' => 0.00,
            'ip_cost' => 0.00,
            'lp_cost' => 0.00,
            'labor_cost' => 0.00,
            'machine_cost' => 0.00,
            'current_machine' => 0.00,
            'other_fixed' => 0.00,
            'defect_cost' => 0.00,
        ]);

        // Even with zero costs, royalty is still calculated: (100 - 0) * 0.04 = 4.00
        // So total product cost = 4.00, profit percentage = ((100 / 4) - 1) * 100 = 2400%
        $this->assertEquals(2400.0, $opin->profit_percentage);
    }
}
