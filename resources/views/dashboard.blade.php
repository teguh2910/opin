@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')
<!-- Info boxes -->
<div class="row">
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Users</span>
                <span class="info-box-number">1</span>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-clock"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Active Sessions</span>
                <span class="info-box-number">1</span>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-chart-line"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">OPIN Records</span>
                <span class="info-box-number">{{ \App\Models\Opin::count() }}</span>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-server"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">System Status</span>
                <span class="info-box-number">Online</span>
            </div>
        </div>
    </div>
</div>

<!-- Main row -->
<div class="row">
    <!-- Left col -->
    <div class="col-md-8">
        <!-- TABLE: LATEST OPIN RECORDS -->
        <div class="card">
            <div class="card-header border-transparent">
                <h3 class="card-title">Latest OPIN Records</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table m-0">
                        <thead>
                            <tr>
                                <th>Part No</th>
                                <th>Part Name</th>
                                <th>Sales Price</th>
                                <th>Gross Income</th>
                                <th>Profit %</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(\App\Models\Opin::latest()->take(5)->get() as $opin)
                                <tr>
                                    <td>{{ $opin->part_no }}</td>
                                    <td>{{ $opin->part_name }}</td>
                                    <td>Rp {{ number_format($opin->sales_price, 0, ',', '.') }}</td>
                                    <td class="{{ $opin->gross_income >= 0 ? 'text-success' : 'text-danger' }}">
                                        Rp {{ number_format($opin->gross_income, 0, ',', '.') }}
                                    </td>
                                    <td class="{{ $opin->profit_percentage >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ number_format($opin->profit_percentage, 2, ',', '.') }}%
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No OPIN records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer clearfix">
                <a href="{{ route('opin.index') }}" class="btn btn-sm btn-secondary float-right">View All OPIN Records</a>
            </div>
        </div>
    </div>

    <!-- Right col -->
    <div class="col-md-4">
        <!-- PRODUCTIVE CHART -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Quick Actions</h3>
            </div>

            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('opin.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus mr-2"></i>Add New OPIN
                    </a>
                    <a href="{{ route('opin.index') }}" class="btn btn-info">
                        <i class="fas fa-list mr-2"></i>View All Records
                    </a>
                    <a href="#" class="btn btn-success">
                        <i class="fas fa-chart-bar mr-2"></i>Generate Report
                    </a>
                </div>
            </div>
        </div>

        <!-- SYSTEM INFO -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">System Information</h3>
            </div>

            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-5">Laravel Version:</dt>
                    <dd class="col-sm-7">{{ app()->version() }}</dd>

                    <dt class="col-sm-5">PHP Version:</dt>
                    <dd class="col-sm-7">{{ PHP_VERSION }}</dd>

                    <dt class="col-sm-5">Database:</dt>
                    <dd class="col-sm-7">SQLite</dd>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection
