<?php

namespace App\Http\Controllers;

use App\Exports\ComponentTemplateExport;
use App\Imports\ComponentImport;
use App\Models\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class ComponentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $components = Component::orderBy('part_no')->paginate(10);

        return view('component.index', compact('components'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('component.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'part_no' => 'required|string|max:20|unique:components,part_no',
            'part_name' => 'required|string|max:255',
            'type' => 'required|in:rm,lp,ip,ckd',
            'unit_cost' => 'required|numeric|min:0|max:999999.99',
            'unit' => 'required|string|max:10',
        ], [
            'part_no.required' => 'Part number is required.',
            'part_no.unique' => 'This part number already exists.',
            'part_name.required' => 'Part name is required.',
            'type.required' => 'Component type is required.',
            'type.in' => 'Invalid component type selected.',
            'unit_cost.required' => 'Unit cost is required.',
            'unit_cost.numeric' => 'Unit cost must be a valid number.',
            'unit_cost.min' => 'Unit cost cannot be negative.',
            'unit.required' => 'Unit of measurement is required.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Component::create($request->only(['part_no', 'part_name', 'type', 'unit_cost', 'unit']));

        return redirect()->route('component.index')
            ->with('success', 'Component created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Component $component)
    {
        return view('component.show', compact('component'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Component $component)
    {
        return view('component.edit', compact('component'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Component $component)
    {
        $validator = Validator::make($request->all(), [
            'part_no' => [
                'required',
                'string',
                'max:20',
                Rule::unique('components')->ignore($component->id),
            ],
            'part_name' => 'required|string|max:255',
            'type' => 'required|in:rm,lp,ip,ckd',
            'unit_cost' => 'required|numeric|min:0|max:999999.99',
            'unit' => 'required|string|max:10',
        ], [
            'part_no.required' => 'Part number is required.',
            'part_no.unique' => 'This part number already exists.',
            'part_name.required' => 'Part name is required.',
            'type.required' => 'Component type is required.',
            'type.in' => 'Invalid component type selected.',
            'unit_cost.required' => 'Unit cost is required.',
            'unit_cost.numeric' => 'Unit cost must be a valid number.',
            'unit_cost.min' => 'Unit cost cannot be negative.',
            'unit.required' => 'Unit of measurement is required.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $component->update($request->only(['part_no', 'part_name', 'type', 'unit_cost', 'unit']));

        return redirect()->route('component.index')
            ->with('success', 'Component updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Component $component)
    {
        // Check if component is used in any BOM entries
        if ($component->billOfMaterials()->exists()) {
            return redirect()->route('component.index')
                ->with('error', 'Cannot delete component because it is used in bill of materials.');
        }

        $component->delete();

        return redirect()->route('component.index')
            ->with('success', 'Component deleted successfully.');
    }

    /**
     * Show the Excel upload form
     */
    public function showUploadForm()
    {
        return view('component.upload');
    }

    /**
     * Handle Excel file upload and import
     */
    public function uploadExcel(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls|max:10240', // 10MB max
        ], [
            'excel_file.required' => 'Please select an Excel file to upload.',
            'excel_file.file' => 'The uploaded file must be a valid file.',
            'excel_file.mimes' => 'Only Excel files (.xlsx, .xls) are allowed.',
            'excel_file.max' => 'File size must not exceed 10MB.',
        ]);

        try {
            $import = new ComponentImport;
            Excel::import($import, $request->file('excel_file'));

            $results = $import->getResults();

            if ($results['error_count'] > 0) {
                $message = "Import completed with issues. {$results['success_count']} components imported successfully, {$results['error_count']} failed.";

                return redirect()->route('component.index')
                    ->with('warning', $message)
                    ->with('import_errors', $results['errors']);
            }

            return redirect()->route('component.index')
                ->with('success', "Successfully imported {$results['success_count']} components.");

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to import Excel file: '.$e->getMessage())
                ->withInput();
        }
    }

    /**
     * Download Excel template for component import
     */
    public function downloadTemplate()
    {
        return Excel::download(new ComponentTemplateExport, 'component_template.xlsx');
    }
}
