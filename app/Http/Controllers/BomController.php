<?php

namespace App\Http\Controllers;

use App\Models\Bom;
use App\Models\Component;
use App\Models\Opin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BomController extends Controller
{
    /**
     * Display a listing of the BOM entries.
     */
    public function index(): View
    {
        $boms = Bom::with(['opin', 'component'])->paginate(25);

        return view('bom.index', compact('boms'));
    }

    /**
     * Show the form for creating a new BOM entry.
     */
    public function create(): View
    {
        $opins = Opin::all();
        $components = Component::all();

        return view('bom.create', compact('opins', 'components'));
    }

    /**
     * Store a newly created BOM entry in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'opin_id' => 'required|exists:opins,id',
            'component_id' => 'required|exists:components,id',
            'quantity' => 'required|numeric|min:0.001',
        ]);

        // Check if BOM entry already exists for this combination
        $existingBom = Bom::where('opin_id', $request->opin_id)
            ->where('component_id', $request->component_id)
            ->first();

        if ($existingBom) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['duplicate' => 'A BOM entry for this finished good and component already exists.']);
        }

        Bom::create($request->all());

        return redirect()->route('bom.index')->with('success', 'BOM entry created successfully.');
    }

    /**
     * Display the specified BOM entry.
     */
    public function show(Bom $bom): View
    {
        $bom->load(['opin', 'component']);

        return view('bom.show', compact('bom'));
    }

    /**
     * Show the form for editing the specified BOM entry.
     */
    public function edit(Bom $bom): View
    {
        $opins = Opin::all();
        $components = Component::all();

        return view('bom.edit', compact('bom', 'opins', 'components'));
    }

    /**
     * Update the specified BOM entry in storage.
     */
    public function update(Request $request, Bom $bom): RedirectResponse
    {
        $request->validate([
            'opin_id' => 'required|exists:opins,id',
            'component_id' => 'required|exists:components,id',
            'quantity' => 'required|numeric|min:0.001',
        ]);

        // Check if BOM entry already exists for this combination (excluding current)
        $existingBom = Bom::where('opin_id', $request->opin_id)
            ->where('component_id', $request->component_id)
            ->where('id', '!=', $bom->id)
            ->first();

        if ($existingBom) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['duplicate' => 'A BOM entry for this finished good and component already exists.']);
        }

        $bom->update($request->all());

        return redirect()->route('bom.index')->with('success', 'BOM entry updated successfully.');
    }

    /**
     * Remove the specified BOM entry from storage.
     */
    public function destroy(Bom $bom): RedirectResponse
    {
        $bom->delete();

        return redirect()->route('bom.index')->with('success', 'BOM entry deleted successfully.');
    }
}
