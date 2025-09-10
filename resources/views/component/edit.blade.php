@extends('layouts.app')

@section('title', 'Edit Component')
@section('page-title', 'Edit Component')
@section('breadcrumb', 'Component Management / Edit Component')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Edit Component</h3>
                <div class="card-tools">
                    <a href="{{ route('component.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Back to Components
                    </a>
                </div>
            </div>

            <form action="{{ route('component.update', $component) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="part_no" class="form-label">Part Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('part_no') is-invalid @enderror"
                                   id="part_no" name="part_no" value="{{ old('part_no', $component->part_no) }}" required
                                   maxlength="20" placeholder="e.g., RM001, CKD001">
                            @error('part_no')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="part_name" class="form-label">Part Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('part_name') is-invalid @enderror"
                                   id="part_name" name="part_name" value="{{ old('part_name', $component->part_name) }}" required
                                   placeholder="e.g., Raw Material, CKD Cost">
                            @error('part_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="type" class="form-label">Component Type <span class="text-danger">*</span></label>
                            <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                                <option value="">Select Type</option>
                                <option value="rm" {{ old('type', $component->type) == 'rm' ? 'selected' : '' }}>RM (Raw Material)</option>
                                <option value="lp" {{ old('type', $component->type) == 'lp' ? 'selected' : '' }}>LP (Local Purchase)</option>
                                <option value="ip" {{ old('type', $component->type) == 'ip' ? 'selected' : '' }}>IP (Import Purchase)</option>
                                <option value="ckd" {{ old('type', $component->type) == 'ckd' ? 'selected' : '' }}>CKD (Completely Knocked Down)</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="unit_cost" class="form-label">Unit Cost (Rp) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('unit_cost') is-invalid @enderror"
                                   id="unit_cost" name="unit_cost" value="{{ old('unit_cost', $component->unit_cost) }}" required
                                   step="0.01" min="0" placeholder="0.00">
                            @error('unit_cost')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="unit" class="form-label">Unit of Measurement <span class="text-danger">*</span></label>
                            <select class="form-control @error('unit') is-invalid @enderror" id="unit" name="unit" required>
                                <option value="">Select Unit</option>
                                <option value="pcs" {{ old('unit', $component->unit) == 'pcs' ? 'selected' : '' }}>Pieces (pcs)</option>
                                <option value="kg" {{ old('unit', $component->unit) == 'kg' ? 'selected' : '' }}>Kilograms (kg)</option>
                                <option value="m" {{ old('unit', $component->unit) == 'm' ? 'selected' : '' }}>Meters (m)</option>
                                <option value="l" {{ old('unit', $component->unit) == 'l' ? 'selected' : '' }}>Liters (l)</option>
                                <option value="box" {{ old('unit', $component->unit) == 'box' ? 'selected' : '' }}>Boxes (box)</option>
                            </select>
                            @error('unit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Component
                    </button>
                    <a href="{{ route('component.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
