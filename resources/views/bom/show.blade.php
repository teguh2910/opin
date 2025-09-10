@extends('layouts.app')

@section('title', 'BOM Entry Details')
@section('page-title', 'BOM Entry Details')
@section('breadcrumb', 'BOM Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>BOM Entry Details</h2>
                <div>
                    <a href="{{ route('bom.edit', $bom) }}" class="btn btn-warning me-2">
                        <i class="fas fa-edit me-1"></i>Edit
                    </a>
                    <a href="{{ route('bom.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to BOM List
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">BOM Entry Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-muted">BOM ID</h6>
                                    <p class="mb-3">#{{ $bom->id }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted">Created At</h6>
                                    <p class="mb-3">{{ $bom->created_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>

                            <hr>

                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-muted">Finished Good</h6>
                                    <p class="mb-1">
                                        <strong>{{ $bom->opin->part_no }}</strong>
                                    </p>
                                    <p class="text-muted">{{ $bom->opin->part_name }}</p>
                                    <a href="{{ route('opin.show', $bom->opin) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i> View Finished Good
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted">Component</h6>
                                    <p class="mb-1">
                                        <strong>{{ $bom->component->part_no }}</strong>
                                    </p>
                                    <p class="text-muted">{{ $bom->component->part_name }}</p>
                                    <div class="mt-2">
                                        <span class="badge badge-info">{{ $bom->component->part_no }}</span>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="row">
                                <div class="col-md-4">
                                    <h6 class="text-muted">Quantity</h6>
                                    <p class="h4 text-primary mb-0">{{ number_format($bom->quantity, 3) }}</p>
                                    <small class="text-muted">{{ $bom->component->unit }}</small>
                                </div>
                                <div class="col-md-4">
                                    <h6 class="text-muted">Unit Cost</h6>
                                    <p class="h4 text-success mb-0">Rp {{ number_format($bom->component->unit_cost, 0, ',', '.') }}</p>
                                    <small class="text-muted">per {{ $bom->component->unit }}</small>
                                </div>
                                <div class="col-md-4">
                                    <h6 class="text-muted">Total Cost</h6>
                                    <p class="h4 text-info mb-0">Rp {{ number_format($bom->total_cost, 0, ',', '.') }}</p>
                                    <small class="text-muted">({{ number_format($bom->quantity, 3) }} × Rp {{ number_format($bom->component->unit_cost, 0, ',', '.') }})</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('bom.edit', $bom) }}" class="btn btn-warning">
                                    <i class="fas fa-edit me-2"></i>Edit Entry
                                </a>
                                <a href="{{ route('bom.create') }}" class="btn btn-success">
                                    <i class="fas fa-plus me-2"></i>Add New Entry
                                </a>
                                <a href="{{ route('bom.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-list me-2"></i>View All Entries
                                </a>
                            </div>

                            <hr>

                            <div class="text-center">
                                <form method="POST" action="{{ route('bom.destroy', $bom) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to delete this BOM entry?')">
                                        <i class="fas fa-trash me-1"></i>Delete Entry
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Related Information</h6>
                        </div>
                        <div class="card-body">
                            <p class="mb-2">
                                <strong>Finished Good:</strong><br>
                                <a href="{{ route('opin.show', $bom->opin) }}">{{ $bom->opin->part_no }}</a>
                            </p>
                            <p class="mb-2">
                                <strong>Component Type:</strong><br>
                                {{ $bom->component->part_name }}
                            </p>
                            <p class="mb-0">
                                <strong>Last Updated:</strong><br>
                                {{ $bom->updated_at->format('d M Y, H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
