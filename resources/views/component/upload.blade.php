@extends('layouts.app')

@section('title', 'Upload Components')
@section('page-title', 'Upload Components')
@section('breadcrumb', 'Component Management > Upload Excel')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Upload Components from Excel</h3>
                <div class="card-tools">
                    <a href="{{ route('component.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Back to Components
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

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-ban"></i> Error!</h5>
                    {{ session('error') }}
                </div>
            @endif

            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="alert alert-info">
                            <h5><i class="icon fas fa-info"></i> Upload Instructions</h5>
                            <ul class="mb-0">
                                <li>Upload an Excel file (.xlsx or .xls) with component data</li>
                                <li>The file must contain headers in the first row</li>
                                <li>Required columns: <strong>part_no, part_name, type, unit_cost, unit</strong></li>
                                <li>Type values must be: rm, lp, ip, or ckd (case insensitive)</li>
                                <li>Existing components will be updated, new ones will be created</li>
                                <li>Maximum file size: 10MB</li>
                            </ul>
                        </div>

                        <form action="{{ route('component.upload.excel') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <label for="excel_file">Select Excel File</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="excel_file" name="excel_file"
                                               accept=".xlsx,.xls" required>
                                        <label class="custom-file-label" for="excel_file">Choose file</label>
                                    </div>
                                </div>
                                @error('excel_file')
                                    <div class="text-danger mt-1">
                                        <small>{{ $message }}</small>
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-upload"></i> Upload and Import
                                </button>
                                <a href="{{ route('component.index') }}" class="btn btn-secondary ml-2">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>

                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Sample Template</h5>
                            </div>
                            <div class="card-body">
                                <p>Download a sample Excel template to ensure correct formatting:</p>
                                <a href="{{ route('component.download.template') }}" class="btn btn-outline-primary btn-block">
                                    <i class="fas fa-download"></i> Download Template
                                </a>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Type Reference</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled">
                                    <li><strong>rm</strong> - Raw Material</li>
                                    <li><strong>lp</strong> - Local Purchase</li>
                                    <li><strong>ip</strong> - Import Purchase</li>
                                    <li><strong>ckd</strong> - Completely Knocked Down</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Update file input label when file is selected
        $('#excel_file').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').html(fileName || 'Choose file');
        });
    });
</script>
@endsection
