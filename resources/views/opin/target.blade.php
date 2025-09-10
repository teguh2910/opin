@extends('layouts.app')

@section('title', 'Target OPIN Calculator')
@section('page-title', 'Target OPIN Calculator')
@section('breadcrumb', 'Target Calculation')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Calculate Required Costs for Target Profit</h3>
                <div class="card-tools">
                    <a href="{{ route('opin.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Back to OPIN List
                    </a>
                </div>
            </div>

            <div class="card-body">
                <!-- Existing OPIN Records -->
                <div class="mb-4">
                    <h5>Select Existing OPIN Record (Optional)</h5>
                    <p class="text-muted">Choose an existing OPIN record to pre-fill the form with its data</p>
                    <select id="existingOpin" class="form-control" onchange="loadOpinData()">
                        <option value="">-- Select OPIN Record --</option>
                        @foreach($opins as $opin)
                            <option value="{{ $opin->id }}" 
                                    data-sales-price="{{ $opin->sales_price }}"
                                    data-rm-cost="{{ $opin->rm_cost }}"
                                    data-ckd-cost="{{ $opin->ckd_cost }}"
                                    data-ip-cost="{{ $opin->ip_cost }}"
                                    data-lp-cost="{{ $opin->lp_cost }}"
                                    data-labor-cost="{{ $opin->labor_cost }}"
                                    data-machine-cost="{{ $opin->machine_cost }}"
                                    data-current-machine="{{ $opin->current_machine }}"
                                    data-other-fixed="{{ $opin->other_fixed }}"
                                    data-defect-cost="{{ $opin->defect_cost }}">
                                {{ $opin->part_no }} - {{ $opin->part_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <form id="targetForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sales_price">Sales Price (Rp)</label>
                                <input type="number" class="form-control" id="sales_price" name="sales_price" step="0.01" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="target_profit">Target Profit (%)</label>
                                <input type="number" class="form-control" id="target_profit" name="target_profit" step="0.01" required>
                            </div>
                        </div>
                    </div>

                    <h5 class="mt-4">Fixed Costs (Rp)</h5>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="labor_cost">Labor Cost</label>
                                <input type="number" class="form-control" id="labor_cost" name="labor_cost" step="0.01" value="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="machine_cost">Machine Cost</label>
                                <input type="number" class="form-control" id="machine_cost" name="machine_cost" step="0.01" value="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="current_machine">Current Machine</label>
                                <input type="number" class="form-control" id="current_machine" name="current_machine" step="0.01" value="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="other_fixed">Other Fixed</label>
                                <input type="number" class="form-control" id="other_fixed" name="other_fixed" step="0.01" value="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="defect_cost">Defect Cost</label>
                                <input type="number" class="form-control" id="defect_cost" name="defect_cost" step="0.01" value="0">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <button type="button" class="btn btn-primary" onclick="calculateTarget()">
                                <i class="fas fa-calculator"></i> Calculate Required Costs
                            </button>
                            <button type="button" class="btn btn-secondary ml-2" onclick="clearForm()">
                                <i class="fas fa-eraser"></i> Clear Form
                            </button>
                        </div>
                    </div>
                </form>

                <div id="results" class="mt-4" style="display: none;">
                    <h5>Calculation Results</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Target Total DM Cost (Rp)</label>
                                <input type="text" class="form-control bg-light font-weight-bold" id="target_dm_result" readonly>
                                <small class="text-muted">Required DM cost for target profit</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Existing Total DM Cost (Rp)</label>
                                <input type="text" class="form-control bg-light" id="existing_dm_result" readonly>
                                <small class="text-muted">Current DM cost from selected OPIN</small>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-info">
                        <strong>Note:</strong> Compare the target DM cost (what you need) with the existing DM cost (what you currently have).
                        This helps you understand the cost adjustments required to achieve your target profit margin.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
function formatNumber(num) {
    return Math.round(num).toLocaleString('id-ID');
}

function parseFormattedNumber(str) {
    // Remove thousand separators (dots) and return as number
    return parseFloat(str.replace(/\./g, '')) || 0;
}

function loadOpinData() {
    const select = document.getElementById('existingOpin');
    const selectedOption = select.options[select.selectedIndex];
    
    if (selectedOption.value) {
        // Format and display sales price
        const salesPrice = parseFloat(selectedOption.getAttribute('data-sales-price')) || 0;
        document.getElementById('sales_price').value = formatNumber(salesPrice);
        
        // Format and display fixed costs
        const laborCost = parseFloat(selectedOption.getAttribute('data-labor-cost')) || 0;
        const machineCost = parseFloat(selectedOption.getAttribute('data-machine-cost')) || 0;
        const currentMachine = parseFloat(selectedOption.getAttribute('data-current-machine')) || 0;
        const otherFixed = parseFloat(selectedOption.getAttribute('data-other-fixed')) || 0;
        const defectCost = parseFloat(selectedOption.getAttribute('data-defect-cost')) || 0;
        
        document.getElementById('labor_cost').value = formatNumber(laborCost);
        document.getElementById('machine_cost').value = formatNumber(machineCost);
        document.getElementById('current_machine').value = formatNumber(currentMachine);
        document.getElementById('other_fixed').value = formatNumber(otherFixed);
        document.getElementById('defect_cost').value = formatNumber(defectCost);
        
        // Calculate and display existing DM cost
        const rmCost = parseFloat(selectedOption.getAttribute('data-rm-cost')) || 0;
        const ckdCost = parseFloat(selectedOption.getAttribute('data-ckd-cost')) || 0;
        const ipCost = parseFloat(selectedOption.getAttribute('data-ip-cost')) || 0;
        const lpCost = parseFloat(selectedOption.getAttribute('data-lp-cost')) || 0;
        const existingDmCost = rmCost + ckdCost + ipCost + lpCost;
        
        document.getElementById('existing_dm_result').value = formatNumber(existingDmCost);
    } else {
        // Clear existing DM cost if no OPIN selected
        document.getElementById('existing_dm_result').value = '';
    }
}

function clearForm() {
    document.getElementById('targetForm').reset();
    document.getElementById('existingOpin').selectedIndex = 0;
    document.getElementById('results').style.display = 'none';
    document.getElementById('target_dm_result').value = '';
    document.getElementById('existing_dm_result').value = '';
    
    // Clear formatted values
    document.getElementById('sales_price').value = '';
    document.getElementById('labor_cost').value = '0';
    document.getElementById('machine_cost').value = '0';
    document.getElementById('current_machine').value = '0';
    document.getElementById('other_fixed').value = '0';
    document.getElementById('defect_cost').value = '0';
}

function calculateTarget() {
    const salesPrice = parseFormattedNumber(document.getElementById('sales_price').value);
    const targetProfit = parseFloat(document.getElementById('target_profit').value);
    const laborCost = parseFormattedNumber(document.getElementById('labor_cost').value);
    const machineCost = parseFormattedNumber(document.getElementById('machine_cost').value);
    const currentMachine = parseFormattedNumber(document.getElementById('current_machine').value);
    const otherFixed = parseFormattedNumber(document.getElementById('other_fixed').value);
    const defectCost = parseFormattedNumber(document.getElementById('defect_cost').value);

    if (!salesPrice || !targetProfit) {
        alert('Please enter Sales Price and Target Profit');
        return;
    }

    // Calculate required total product cost for target profit
    const requiredRatio = (targetProfit / 100) + 1;
    const requiredTotalCost = salesPrice / requiredRatio;

    // Fixed costs
    const fixedCosts = laborCost + machineCost + currentMachine + otherFixed + defectCost;

    // SG&A (6.55% of product cost without common cost, but we need to estimate)
    // This is approximate - we'll iterate to find the correct value
    let productCostWithoutCommon = requiredTotalCost - (requiredTotalCost * 0.0655) - ((salesPrice - 0) * 0.04); // Approximate royalty
    let sga = productCostWithoutCommon * 0.0655;
    let royalty = (salesPrice - 0) * 0.04; // Approximate
    let totalCost = productCostWithoutCommon + sga + royalty;

    // Adjust for accuracy
    for (let i = 0; i < 10; i++) {
        productCostWithoutCommon = requiredTotalCost - sga - royalty;
        sga = productCostWithoutCommon * 0.0655;
        royalty = (salesPrice - 0) * 0.04; // Still approximate
        totalCost = productCostWithoutCommon + sga + royalty;
    }

    // Variable costs (Total DM - RM, CKD, IP, LP combined)
    const variableCosts = productCostWithoutCommon - fixedCosts;

    if (variableCosts < 0) {
        alert('Fixed costs are too high for the target profit. Please reduce fixed costs or increase target profit.');
        return;
    }

    // Display target DM cost
    document.getElementById('target_dm_result').value = formatNumber(variableCosts);

    document.getElementById('results').style.display = 'block';
}
</script>
@endsection
