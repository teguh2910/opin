<?php

namespace App\Imports;

use App\Models\Component;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ComponentImport implements SkipsOnFailure, ToCollection, WithHeadingRow, WithValidation
{
    use SkipsFailures;

    private $errors = [];

    private $successCount = 0;

    private $errorCount = 0;

    private $processedPartNos = [];

    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            // Skip empty rows
            if (empty(array_filter($row->toArray()))) {
                continue;
            }

            $partNo = $row['part_no'] ?? null;

            // Check for duplicates within the same file
            if ($partNo && in_array($partNo, $this->processedPartNos)) {
                $this->errors[] = [
                    'row' => $row->toArray(),
                    'errors' => ["Duplicate part number '{$partNo}' found in the same file."],
                ];
                $this->errorCount++;

                continue;
            }

            $validator = Validator::make($row->toArray(), $this->rules(), $this->customValidationMessages());

            if ($validator->fails()) {
                $this->errors[] = [
                    'row' => $row->toArray(),
                    'errors' => $validator->errors()->all(),
                ];
                $this->errorCount++;

                continue;
            }

            // Add to processed part numbers
            if ($partNo) {
                $this->processedPartNos[] = $partNo;
            }

            try {
                Component::updateOrCreate(
                    ['part_no' => $row['part_no']],
                    [
                        'part_no' => $row['part_no'],
                        'part_name' => $row['part_name'],
                        'type' => strtolower($row['type']),
                        'unit_cost' => $row['unit_cost'],
                        'unit' => $row['unit'],
                    ]
                );
                $this->successCount++;
            } catch (\Exception $e) {
                $this->errors[] = [
                    'row' => $row->toArray(),
                    'errors' => ['Database error: '.$e->getMessage()],
                ];
                $this->errorCount++;
            }
        }
    }

    /**
     * Validation rules for each row
     */
    public function rules(): array
    {
        return [
            'part_no' => 'required|string|max:20',
            'part_name' => 'required|string|max:255',
            'type' => 'required|in:rm,lp,ip,ckd,RM,LP,IP,CKD',
            'unit_cost' => 'required|numeric|min:0|max:999999.99',
            'unit' => 'required|string|max:10',
        ];
    }

    /**
     * Custom validation messages
     */
    public function customValidationMessages(): array
    {
        return [
            'part_no.required' => 'Part number is required.',
            'part_no.unique' => 'Part number :input already exists.',
            'part_name.required' => 'Part name is required.',
            'type.required' => 'Component type is required.',
            'type.in' => 'Type must be one of: rm, lp, ip, ckd (case insensitive).',
            'unit_cost.required' => 'Unit cost is required.',
            'unit_cost.numeric' => 'Unit cost must be a valid number.',
            'unit_cost.min' => 'Unit cost cannot be negative.',
            'unit.required' => 'Unit of measurement is required.',
        ];
    }

    /**
     * Get import results
     */
    public function getResults(): array
    {
        return [
            'success_count' => $this->successCount,
            'error_count' => $this->errorCount,
            'errors' => $this->errors,
        ];
    }
}
