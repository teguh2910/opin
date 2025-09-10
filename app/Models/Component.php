<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Component extends Model
{
    use HasFactory;

    protected $fillable = [
        'part_no',
        'part_name',
        'type',
        'unit_cost',
        'unit',
    ];

    protected $casts = [
        'unit_cost' => 'decimal:2',
    ];

    /**
     * Get the bill of materials entries for this component
     */
    public function billOfMaterials(): HasMany
    {
        return $this->hasMany(Bom::class);
    }
}
