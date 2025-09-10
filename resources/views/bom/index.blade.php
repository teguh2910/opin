@extends('layouts.app')

@section('title', 'Bill of Materials')
@section('page-title', 'Bill of Materials')
@section('breadcrumb', 'BOM Management')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Bill of Materials</h3>
                <div class="card-tools">
                    <a href="{{ route('bom.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Add BOM Entry
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-check"></i> Success!</h5>
                    {{ session('success') }}
                </div>
            @endif

            <div class="card-body">
                <div class="table-responsive">
                    <table id="bomTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Finished Good</th>
                                <th>Component</th>
                                <th>Part No</th>
                                <th>Quantity</th>
                                <th>Unit Cost</th>
                                <th>Total Cost</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($boms as $bom)
                                <tr>
                                    <td>{{ $bom->id }}</td>
                                    <td>{{ $bom->opin->part_no }} - {{ $bom->opin->part_name }}</td>
                                    <td>{{ $bom->component->part_name }}</td>
                                    <td>
                                        <span class="badge badge-info">{{ $bom->component->part_no }}</span>
                                    </td>
                                    <td>{{ number_format($bom->quantity, 3) }} {{ $bom->component->unit }}</td>
                                    <td>Rp {{ number_format($bom->component->unit_cost, 0, ',', '.') }}</td>
                                    <td class="font-weight-bold">Rp {{ number_format($bom->total_cost, 0, ',', '.') }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('bom.show', $bom) }}" class="btn btn-sm btn-info" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('bom.edit', $bom) }}" class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('bom.destroy', $bom) }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this BOM entry?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($boms->hasPages())
                    <div class="d-flex justify-content-center">
                        {{ $boms->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#bomTable').DataTable({
        "responsive": true,
        "scrollX": true,
        "lengthChange": true,
        "autoWidth": false,
        "pageLength": 25,
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        "order": [[0, "desc"]]
    });
});
</script>
@endsection
