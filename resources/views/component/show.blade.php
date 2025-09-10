@extends('layouts.app')

@section('title', 'Component Details')
@section('page-title', 'Component Details')
@section('breadcrumb', 'Component Management / Component Details')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Component Details</h3>
                <div class="card-tools">
                    <a href="{{ route('component.edit', $component) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="{{ route('component.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Back to Components
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted">Part Information</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td width="120"><strong>Part No:</strong></td>
                                <td>
                                    <span class="badge badge-info">{{ $component->part_no }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Part Name:</strong></td>
                                <td>{{ $component->part_name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Type:</strong></td>
                                <td>
                                    <span class="badge badge-secondary">{{ strtoupper($component->type) }}</span>
                                    @if($component->type == 'rm')
                                        <small class="text-muted">(Raw Material)</small>
                                    @elseif($component->type == 'lp')
                                        <small class="text-muted">(Local Purchase)</small>
                                    @elseif($component->type == 'ip')
                                        <small class="text-muted">(Import Purchase)</small>
                                    @elseif($component->type == 'ckd')
                                        <small class="text-muted">(Completely Knocked Down)</small>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Unit Cost:</strong></td>
                                <td>
                                    <span class="h5 text-success">Rp {{ number_format($component->unit_cost, 0, ',', '.') }}</span>
                                    <small class="text-muted">per {{ $component->unit }}</small>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Unit:</strong></td>
                                <td>{{ $component->unit }}</td>
                            </tr>
                        </table>
                    </div>

                    <div class="col-md-6">
                        <h6 class="text-muted">Usage Information</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td width="120"><strong>BOM Entries:</strong></td>
                                <td>{{ $component->billOfMaterials->count() }}</td>
                            </tr>
                            <tr>
                                <td><strong>Created:</strong></td>
                                <td>{{ $component->created_at->format('d M Y, H:i') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Last Updated:</strong></td>
                                <td>{{ $component->updated_at->format('d M Y, H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                @if($component->billOfMaterials->count() > 0)
                    <hr>
                    <h6 class="text-muted">Used in Bill of Materials</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Finished Good</th>
                                    <th>Quantity</th>
                                    <th>Total Cost</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($component->billOfMaterials as $bom)
                                    <tr>
                                        <td>{{ $bom->opin->part_no }} - {{ $bom->opin->part_name }}</td>
                                        <td>{{ number_format($bom->quantity, 3) }} {{ $component->unit }}</td>
                                        <td>Rp {{ number_format($bom->getTotalCostAttribute(), 0, ',', '.') }}</td>
                                        <td>
                                            <a href="{{ route('bom.show', $bom) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i> View BOM
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <div class="card-footer">
                <div class="row">
                    <div class="col-md-6">
                        <form action="{{ route('component.destroy', $component) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Are you sure you want to delete this component? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" {{ $component->billOfMaterials->count() > 0 ? 'disabled' : '' }}>
                                <i class="fas fa-trash"></i> Delete Component
                            </button>
                            @if($component->billOfMaterials->count() > 0)
                                <small class="text-muted ml-2">Cannot delete - component is used in BOM entries</small>
                            @endif
                        </form>
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="{{ route('component.edit', $component) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit Component
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
