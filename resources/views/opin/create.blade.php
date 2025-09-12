@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Create New OPIN</h2>
                    <a href="{{ route('opin.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to List
                    </a>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">OPIN Details</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('opin.store') }}">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="part_no" class="form-label">Part No</label>
                                    <input type="text" class="form-control @error('part_no') is-invalid @enderror"
                                        id="part_no" name="part_no" value="{{ old('part_no') }}" required>
                                    @error('part_no')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="part_name" class="form-label">Part Name</label>
                                    <input type="text" class="form-control @error('part_name') is-invalid @enderror"
                                        id="part_name" name="part_name" value="{{ old('part_name') }}" required>
                                    @error('part_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="sales_price" class="form-label">Sales Price</label>
                                    <input type="number" step="0.01"
                                        class="form-control @error('sales_price') is-invalid @enderror" id="sales_price"
                                        name="sales_price" value="{{ old('sales_price') }}" required>
                                    @error('sales_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="labor_cost" class="form-label">Labor Cost</label>
                                    <input type="number" step="0.01"
                                        class="form-control @error('labor_cost') is-invalid @enderror" id="labor_cost"
                                        name="labor_cost" value="{{ old('labor_cost') }}" required>
                                    @error('labor_cost')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="machine_cost" class="form-label">Machine Cost</label>
                                    <input type="number" step="0.01"
                                        class="form-control @error('machine_cost') is-invalid @enderror" id="machine_cost"
                                        name="machine_cost" value="{{ old('machine_cost') }}" required>
                                    @error('machine_cost')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="current_machine" class="form-label">Current Machine</label>
                                    <input type="number" step="0.01"
                                        class="form-control @error('current_machine') is-invalid @enderror"
                                        id="current_machine" name="current_machine" value="{{ old('current_machine') }}"
                                        required>
                                    @error('current_machine')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="other_fixed" class="form-label">Other Fixed</label>
                                    <input type="number" step="0.01"
                                        class="form-control @error('other_fixed') is-invalid @enderror" id="other_fixed"
                                        name="other_fixed" value="{{ old('other_fixed') }}" required>
                                    @error('other_fixed')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="defect_cost" class="form-label">Defect Cost</label>
                                    <input type="number" step="0.01"
                                        class="form-control @error('defect_cost') is-invalid @enderror" id="defect_cost"
                                        name="defect_cost" value="{{ old('defect_cost') }}" required>
                                    @error('defect_cost')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="sg_a_percentage" class="form-label">SG&A Percentage (%)</label>
                                    <input type="number" step="0.01" min="0" max="100"
                                        class="form-control @error('sg_a_percentage') is-invalid @enderror"
                                        id="sg_a_percentage" name="sg_a_percentage"
                                        value="{{ old('sg_a_percentage', 6.55) }}" required>
                                    @error('sg_a_percentage')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Create OPIN
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
