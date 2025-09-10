@extends('layouts.app')

@section('title', 'Components')
@section('page-title', 'Components')
@section('breadcrumb', 'Component Management')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Components</h3>
                <div class="card-tools">
                    <a href="{{ route('component.upload.form') }}" class="btn btn-success btn-sm mr-2">
                        <i class="fas fa-file-excel"></i> Upload Excel
                    </a>
                    <a href="{{ route('component.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Add Component
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

            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-exclamation-triangle"></i> Import Warning!</h5>
                    {{ session('warning') }}
                </div>
            @endif

            @if(session('import_errors'))
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-exclamation-circle"></i> Import Errors</h5>
                    <p>The following errors occurred during import:</p>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Row Data</th>
                                    <th>Errors</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(session('import_errors') as $error)
                                    <tr>
                                        <td>
                                            <small>
                                                @if(isset($error['row']))
                                                    Part No: {{ $error['row']['part_no'] ?? 'N/A' }}<br>
                                                    Part Name: {{ $error['row']['part_name'] ?? 'N/A' }}<br>
                                                    Type: {{ $error['row']['type'] ?? 'N/A' }}<br>
                                                    Unit Cost: {{ $error['row']['unit_cost'] ?? 'N/A' }}<br>
                                                    Unit: {{ $error['row']['unit'] ?? 'N/A' }}
                                                @endif
                                            </small>
                                        </td>
                                        <td>
                                            <ul class="mb-0">
                                                @foreach($error['errors'] as $errorMessage)
                                                    <li><small class="text-danger">{{ $errorMessage }}</small></li>
                                                @endforeach
                                            </ul>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <div class="card-body">
                @if($components->count() > 0)
                    <div class="table-responsive">
                        <table id="componentTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Part No</th>
                                    <th>Part Name</th>
                                    <th>Type</th>
                                    <th>Unit Cost</th>
                                    <th>Unit</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($components as $component)
                                    <tr>
                                        <td>{{ $component->id }}</td>
                                        <td>
                                            {{ $component->part_no }}
                                        </td>
                                        <td>{{ $component->part_name }}</td>
                                        <td>
                                            <span class="badge badge-secondary">{{ strtoupper($component->type) }}</span>
                                        </td>
                                        <td>Rp {{ number_format($component->unit_cost, 0, ',', '.') }}</td>
                                        <td>{{ $component->unit }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('component.show', $component) }}" class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('component.edit', $component) }}" class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('component.destroy', $component) }}" method="POST" class="d-inline"
                                                      onsubmit="return confirm('Are you sure you want to delete this component?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $components->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-cogs fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">No Components Found</h4>
                        <p class="text-muted">Get started by adding your first component.</p>
                        <a href="{{ route('component.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add First Component
                        </a>
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
        $('#componentTable').DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#componentTable_wrapper .col-md-6:eq(0)');
    });
</script>
@endsection
