@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>OPIN Details</h2>
                <div>
                    <a href="{{ route('opin.edit', $opin) }}" class="btn btn-warning me-2">
                        <i class="fas fa-edit me-2"></i>Edit
                    </a>
                    <a href="{{ route('opin.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to List
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Part: {{ $opin->part_name }} ({{ $opin->part_no }})</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Basic Information</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Part No:</strong></td>
                                    <td>{{ $opin->part_no }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Part Name:</strong></td>
                                    <td>{{ $opin->part_name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Sales Price:</strong></td>
                                    <td>Rp {{ number_format($opin->sales_price, 0, ',', '.') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Cost Breakdown</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>RM Cost:</strong></td>
                                    <td>Rp {{ number_format($opin->rm_cost, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>CKD Cost:</strong></td>
                                    <td>Rp {{ number_format($opin->ckd_cost, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>IP Cost:</strong></td>
                                    <td>Rp {{ number_format($opin->ip_cost, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>LP Cost:</strong></td>
                                    <td>Rp {{ number_format($opin->lp_cost, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Labor Cost:</strong></td>
                                    <td>Rp {{ number_format($opin->labor_cost, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Machine Cost:</strong></td>
                                    <td>Rp {{ number_format($opin->machine_cost, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Current Machine:</strong></td>
                                    <td>Rp {{ number_format($opin->current_machine, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Other Fixed:</strong></td>
                                    <td>Rp {{ number_format($opin->other_fixed, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Defect Cost:</strong></td>
                                    <td>Rp {{ number_format($opin->defect_cost, 0, ',', '.') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-12">
                            <h6>Detailed Profit Analysis</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Product Cost W/O Common Cost:</strong></td>
                                            <td>Rp {{ number_format($opin->product_cost_without_common_cost, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Gross Income:</strong></td>
                                            <td class="{{ $opin->gross_income >= 0 ? 'text-success' : 'text-danger' }}">
                                                Rp {{ number_format($opin->gross_income, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>SG&A (6.55%):</strong></td>
                                            <td>Rp {{ number_format($opin->sg_a, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Royalty (4.00%):</strong></td>
                                            <td>Rp {{ number_format($opin->royalty, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Total Product Cost:</strong></td>
                                            <td>Rp {{ number_format($opin->total_product_cost, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Profit %:</strong></td>
                                            <td class="{{ $opin->profit_percentage >= 0 ? 'text-success' : 'text-danger' }}">
                                                {{ number_format($opin->profit_percentage, 2, ',', '.') }}%
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h6>Summary Cards</h6>
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <div class="card text-center">
                                                <div class="card-body">
                                                    <h5 class="card-title">Total Product Cost</h5>
                                                    <p class="card-text h4 text-danger">Rp {{ number_format($opin->total_product_cost, 0, ',', '.') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <div class="card text-center">
                                                <div class="card-body">
                                                    <h5 class="card-title">Gross Income</h5>
                                                    <p class="card-text h4 {{ $opin->gross_income >= 0 ? 'text-success' : 'text-danger' }}">Rp {{ number_format($opin->gross_income, 0, ',', '.') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="card text-center">
                                                <div class="card-body">
                                                    <h5 class="card-title">Profit Percentage</h5>
                                                    <p class="card-text h4 {{ $opin->profit_percentage >= 0 ? 'text-success' : 'text-danger' }}">{{ number_format($opin->profit_percentage, 2, ',', '.') }}%</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
