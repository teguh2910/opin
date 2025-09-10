<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bom extends Model
{
    use HasFactory;

    protected $table = 'bill_of_materials';

    protected $fillable = [
        'opin_id',
        'component_id',
        'quantity',
    ];

    protected $casts = [
        'quantity' => 'decimal:3',
    ];

    /**
     * Get the finished good (opin) this BOM entry belongs to
     */
    public function opin(): BelongsTo
    {
        return $this->belongsTo(Opin::class);
    }

    /**
     * Get the component for this BOM entry
     */
    public function component(): BelongsTo
    {
        return $this->belongsTo(Component::class);
    }

    /**
     * Calculate the total cost for this BOM entry (quantity * unit_cost)
     */
    public function getTotalCostAttribute(): float
    {
        return $this->quantity * $this->component->unit_cost;
    }
}
