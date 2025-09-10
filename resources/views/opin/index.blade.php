@extends('layouts.app')

@section('title', 'OPIN Management')
@section('page-title', 'OPIN Management')
@section('breadcrumb', 'OPIN Records')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">OPIN Records</h3>
                <div class="card-tools">
                    <a href="{{ route('opin.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Add New OPIN
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
                    <table id="opinTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Part No</th>
                                <th>Part Name</th>
                                <th>Sales Price</th>
                                <th>Product Cost W/O Common Cost</th>
                                <th>Gross Income</th>
                                <th>SG&A</th>
                                <th>Royalty</th>
                                <th>Total Product Cost</th>
                                <th>Profit %</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($opins as $opin)
                                <tr>
                                    <td>{{ $opin->id }}</td>
                                    <td>{{ $opin->part_no }}</td>
                                    <td>{{ $opin->part_name }}</td>
                                    <td>Rp {{ number_format($opin->sales_price, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($opin->product_cost_without_common_cost, 0, ',', '.') }}</td>
                                    <td class="{{ $opin->gross_income >= 0 ? 'text-success font-weight-bold' : 'text-danger font-weight-bold' }}">
                                        Rp {{ number_format($opin->gross_income, 0, ',', '.') }}
                                    </td>
                                    <td>Rp {{ number_format($opin->sg_a, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($opin->royalty, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($opin->total_product_cost, 0, ',', '.') }}</td>
                                    <td class="{{ $opin->profit_percentage >= 0 ? 'text-success font-weight-bold' : 'text-danger font-weight-bold' }}">
                                        {{ number_format($opin->profit_percentage, 2, ',', '.') }}%
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('opin.show', $opin) }}" class="btn btn-sm btn-info" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('opin.edit', $opin) }}" class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('opin.destroy', $opin) }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this record?')">
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
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#opinTable').DataTable({
        "responsive": true,
        "scrollX": true,
        "lengthChange": true,
        "autoWidth": false,
        "pageLength": 25,
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
    });
});
</script>
@endsection
