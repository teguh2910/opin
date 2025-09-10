<?php

namespace App\Http\Controllers;

use App\Models\Opin;
use Illuminate\Http\Request;

class OpinController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $opins = Opin::all();

        return view('opin.index', compact('opins'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('opin.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'part_no' => 'required|string|max:255',
            'part_name' => 'required|string|max:255',
            'sales_price' => 'required|numeric|min:0',
            'labor_cost' => 'required|numeric|min:0',
            'machine_cost' => 'required|numeric|min:0',
            'current_machine' => 'required|numeric|min:0',
            'other_fixed' => 'required|numeric|min:0',
            'defect_cost' => 'required|numeric|min:0',
        ]);

        Opin::create($request->all());

        return redirect()->route('opin.index')->with('success', 'OPIN created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $opin = Opin::findOrFail($id);

        return view('opin.show', compact('opin'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $opin = Opin::findOrFail($id);

        return view('opin.edit', compact('opin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'part_no' => 'required|string|max:255',
            'part_name' => 'required|string|max:255',
            'sales_price' => 'required|numeric|min:0',
            'labor_cost' => 'required|numeric|min:0',
            'machine_cost' => 'required|numeric|min:0',
            'current_machine' => 'required|numeric|min:0',
            'other_fixed' => 'required|numeric|min:0',
            'defect_cost' => 'required|numeric|min:0',
        ]);

        $opin = Opin::findOrFail($id);
        $opin->update($request->all());

        return redirect()->route('opin.index')->with('success', 'OPIN updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $opin = Opin::findOrFail($id);
        $opin->delete();

        return redirect()->route('opin.index')->with('success', 'OPIN deleted successfully.');
    }

    /**
     * Show the target calculation form.
     */
    public function target()
    {
        $opins = Opin::all();

        return view('opin.target', compact('opins'));
    }
}
