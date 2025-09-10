<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Opin extends Model
{
    use HasFactory;

    protected $fillable = [
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

    /**
     * Calculate Product Cost Without Common Cost
     */
    public function getProductCostWithoutCommonCostAttribute()
    {
        return $this->rm_cost + $this->ckd_cost + $this->ip_cost + $this->lp_cost +
               $this->labor_cost + $this->machine_cost + $this->current_machine +
               $this->other_fixed + $this->defect_cost;
    }

    /**
     * Calculate Gross Income
     */
    public function getGrossIncomeAttribute()
    {
        return $this->sales_price - $this->product_cost_without_common_cost;
    }

    /**
     * Calculate SG&A (6.55% of Product Cost Without Common Cost)
     */
    public function getSgaAttribute()
    {
        return $this->product_cost_without_common_cost * 0.0655;
    }

    /**
     * Calculate Royalty ((sales_price - ckd_cost) x 4.00%)
     */
    public function getRoyaltyAttribute()
    {
        return ($this->sales_price - $this->ckd_cost) * 0.04;
    }

    /**
     * Calculate Total Product Cost
     */
    public function getTotalProductCostAttribute()
    {
        return $this->product_cost_without_common_cost + $this->sg_a + $this->royalty;
    }

    /**
     * Calculate Profit Percentage
     */
    public function getProfitPercentageAttribute()
    {
        $totalCost = $this->total_product_cost;
        if ($totalCost <= 0) {
            return 0.00;
        }
        return (($this->sales_price / $totalCost) - 1) * 100;
    }
}
