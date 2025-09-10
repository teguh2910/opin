@extends('layouts.app')

@section('title', 'Edit BOM Entry')
@section('page-title', 'Edit BOM Entry')
@section('breadcrumb', 'BOM Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Edit BOM Entry</h2>
                <a href="{{ route('bom.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to BOM List
                </a>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">BOM Entry Details</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('bom.update', $bom) }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="opin_id" class="form-label">Finished Good <span class="text-danger">*</span></label>
                                <select class="form-control @error('opin_id') is-invalid @enderror" id="opin_id" name="opin_id" required>
                                    <option value="">Select Finished Good</option>
                                    @foreach($opins as $opin)
                                        <option value="{{ $opin->id }}" {{ old('opin_id', $bom->opin_id) == $opin->id ? 'selected' : '' }}>
                                            {{ $opin->part_no }} - {{ $opin->part_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('opin_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="component_id" class="form-label">Component <span class="text-danger">*</span></label>
                                <select class="form-control @error('component_id') is-invalid @enderror" id="component_id" name="component_id" required>
                                    <option value="">Select Component</option>
                                    @foreach($components as $component)
                                        <option value="{{ $component->id }}" {{ old('component_id', $bom->component_id) == $component->id ? 'selected' : '' }}>
                                            {{ $component->part_no }} - {{ $component->part_name }} (Rp {{ number_format($component->unit_cost, 0, ',', '.') }}/{{ $component->unit }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('component_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                                <input type="number" step="0.001" min="0.001" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" value="{{ old('quantity', $bom->quantity) }}" required>
                                <small class="form-text text-muted">Enter the quantity of this component used in the finished good</small>
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <div id="cost-preview" class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">Cost Preview</h6>
                                        <p class="mb-1">Unit Cost: <span id="unit-cost">Rp {{ number_format($bom->component->unit_cost, 0, ',', '.') }}</span></p>
                                        <p class="mb-1">Quantity: <span id="preview-quantity">{{ number_format($bom->quantity, 3) }}</span></p>
                                        <p class="mb-0"><strong>Total Cost: <span id="total-cost">Rp {{ number_format($bom->total_cost, 0, ',', '.') }}</span></strong></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @error('duplicate')
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle"></i> {{ $message }}
                            </div>
                        @enderror

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update BOM Entry
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    function updateCostPreview() {
        const componentId = $('#component_id').val();
        const quantity = parseFloat($('#quantity').val()) || 0;

        if (componentId) {
            // Find the selected component option
            const selectedOption = $(`#component_id option[value="${componentId}"]`);
            const optionText = selectedOption.text();

            // Extract unit cost from option text (format: "CODE - NAME (Rp X,XXX/UNIT)")
            const costMatch = optionText.match(/Rp ([\d,]+)/);
            if (costMatch) {
                const unitCost = parseFloat(costMatch[1].replace(/,/g, ''));
                const totalCost = unitCost * quantity;

                $('#unit-cost').text('Rp ' + unitCost.toLocaleString('id-ID'));
                $('#preview-quantity').text(quantity.toFixed(3));
                $('#total-cost').text('Rp ' + totalCost.toLocaleString('id-ID'));
            }
        } else {
            $('#unit-cost').text('Rp 0');
            $('#preview-quantity').text('0');
            $('#total-cost').text('Rp 0');
        }
    }

    $('#component_id, #quantity').on('change input', updateCostPreview);
    updateCostPreview(); // Initial calculation
});
</script>
@endsection
