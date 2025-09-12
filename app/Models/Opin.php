<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Opin extends Model
{
    use HasFactory;

    protected $fillable = [
        'part_no',
        'part_name',
        'sales_price',
        'labor_cost',
        'machine_cost',
        'current_machine',
        'other_fixed',
        'defect_cost',
        'sg_a_percentage',
    ];

    protected $casts = [
        'sales_price' => 'decimal:2',
        'labor_cost' => 'decimal:2',
        'machine_cost' => 'decimal:2',
        'current_machine' => 'decimal:2',
        'other_fixed' => 'decimal:2',
        'defect_cost' => 'decimal:2',
        'sg_a_percentage' => 'decimal:4',
    ];

    /**
     * Get the bill of materials for this finished good
     */
    public function billOfMaterials(): HasMany
    {
        return $this->hasMany(Bom::class);
    }

    /**
     * Get RM cost from BOM
     */
    public function getRmCostAttribute(): float
    {
        return $this->getComponentCost('rm');
    }

    /**
     * Get CKD cost from BOM
     */
    public function getCkdCostAttribute(): float
    {
        return $this->getComponentCost('ckd');
    }

    /**
     * Get IP cost from BOM
     */
    public function getIpCostAttribute(): float
    {
        return $this->getComponentCost('ip');
    }

    /**
     * Get LP cost from BOM
     */
    public function getLpCostAttribute(): float
    {
        return $this->getComponentCost('lp');
    }

    /**
     * Get cost for a specific component type from BOM
     */
    private function getComponentCost(string $componentCode): float
    {
        $totalCost = 0;

        $bomEntries = $this->billOfMaterials()
            ->whereHas('component', function ($query) use ($componentCode) {
                $query->where('type', $componentCode);
            })
            ->with('component')
            ->get();

        foreach ($bomEntries as $bomEntry) {
            $totalCost += $bomEntry->total_cost;
        }

        return $totalCost;
    }

    /**
     * Calculate Product Cost Without Common Cost
     */
    public function getProductCostWithoutCommonCostAttribute(): float
    {
        return $this->rm_cost + $this->ckd_cost + $this->ip_cost + $this->lp_cost +
               $this->labor_cost + $this->machine_cost + $this->current_machine +
               $this->other_fixed + $this->defect_cost;
    }

    /**
     * Calculate Gross Income
     */
    public function getGrossIncomeAttribute(): float
    {
        return $this->sales_price - $this->product_cost_without_common_cost;
    }

    /**
     * Calculate SG&A (dynamic percentage of Product Cost Without Common Cost)
     */
    public function getSgaAttribute(): float
    {
        return $this->product_cost_without_common_cost * $this->sg_a_percentage;
    }

    /**
     * Calculate Royalty (4.00% of Sales Price - CKD Cost)
     */
    public function getRoyaltyAttribute(): float
    {
        return ($this->sales_price - $this->ckd_cost) * 0.04;
    }

    /**
     * Calculate Total Product Cost
     */
    public function getTotalProductCostAttribute(): float
    {
        return $this->product_cost_without_common_cost + $this->sg_a + $this->royalty;
    }

    /**
     * Calculate Profit Percentage
     */
    public function getProfitPercentageAttribute(): float
    {
        $totalCost = $this->total_product_cost;
        if ($totalCost <= 0) {
            return 0.00;
        }

        return (($this->sales_price / $totalCost) - 1) * 100;
    }
}
