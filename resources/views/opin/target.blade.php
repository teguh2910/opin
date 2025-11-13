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
                            @foreach ($opins as $opin)
                                <option value="{{ $opin->id }}" data-sales-price="{{ $opin->sales_price }}"
                                    data-rm-cost="{{ $opin->rm_cost }}" data-ckd-cost="{{ $opin->ckd_cost }}"
                                    data-ip-cost="{{ $opin->ip_cost }}" data-lp-cost="{{ $opin->lp_cost }}"
                                    data-labor-cost="{{ $opin->labor_cost }}" data-machine-cost="{{ $opin->machine_cost }}"
                                    data-current-machine="{{ $opin->current_machine }}"
                                    data-other-fixed="{{ $opin->other_fixed }}"
                                    data-defect-cost="{{ $opin->defect_cost }}"
                                    data-sg-a-percentage="{{ $opin->sg_a_percentage }}"
                                    data-total-product-cost="{{ $opin->total_product_cost }}"
                                    data-calculated-total-product-cost="{{ $opin->calculated_total_product_cost }}"
                                    data-profit-percentage="{{ $opin->profit_percentage }}">
                                    {{ $opin->part_no }} - {{ $opin->part_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <form id="targetForm">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="sales_price">Sales Price (Rp)</label>
                                    <input type="number" class="form-control" id="sales_price" name="sales_price"
                                        step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="target_profit_percentage">Target Profit (%)</label>
                                    <input type="number" class="form-control" id="target_profit_percentage"
                                        name="target_profit_percentage" step="0.01" onchange="calculateFromPercentage()">
                                    <small class="text-muted">Or enter amount below</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="target_profit_amount">Target Profit (Rp)</label>
                                    <input type="number" class="form-control" id="target_profit_amount"
                                        name="target_profit_amount" step="0.01" onchange="calculateFromAmount()">
                                    <small class="text-muted">Or enter percentage above</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="sg_a_percentage">SG&A Percentage (%)</label>
                                    <input type="number" class="form-control" id="sg_a_percentage" name="sg_a_percentage"
                                        step="0.01" value="6.55" required>
                                </div>
                            </div>
                        </div>

                        <h5 class="mt-4">Fixed Costs (Rp)</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="labor_cost">Labor Cost</label>
                                    <input type="number" class="form-control" id="labor_cost" name="labor_cost"
                                        step="0.01" value="0">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="machine_cost">Machine Cost</label>
                                    <input type="number" class="form-control" id="machine_cost" name="machine_cost"
                                        step="0.01" value="0">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="current_machine">Current Machine</label>
                                    <input type="number" class="form-control" id="current_machine" name="current_machine"
                                        step="0.01" value="0">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="other_fixed">Other Fixed</label>
                                    <input type="number" class="form-control" id="other_fixed" name="other_fixed"
                                        step="0.01" value="0">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="defect_cost">Defect Cost</label>
                                    <input type="number" class="form-control" id="defect_cost" name="defect_cost"
                                        step="0.01" value="0">
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

                        <!-- Cost Comparison Section -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card border-primary">
                                    <div class="card-header bg-primary text-white">
                                        <h6 class="mb-0">Sales Price vs Total Product Cost</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-6">
                                                <label class="font-weight-bold">Sales Price (Rp)</label>
                                                <input type="text" class="form-control bg-light"
                                                    id="result_sales_price" readonly>
                                            </div>
                                            <div class="col-6">
                                                <label class="font-weight-bold">Total Product Cost (Rp)</label>
                                                <input type="text" class="form-control bg-light"
                                                    id="result_total_product_cost" readonly>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-6">
                                                <label class="font-weight-bold">Current Profit (Rp)</label>
                                                <input type="text"
                                                    class="form-control bg-success text-white font-weight-bold"
                                                    id="result_profit_amount" readonly>
                                            </div>
                                            <div class="col-6">
                                                <label class="font-weight-bold">Current Profit (%)</label>
                                                <input type="text"
                                                    class="form-control bg-success text-white font-weight-bold"
                                                    id="result_profit_percentage" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-warning">
                                    <div class="card-header bg-warning">
                                        <h6 class="mb-0">Target vs Current Comparison</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-6">
                                                <label class="font-weight-bold">Target Profit (%)</label>
                                                <input type="text" class="form-control bg-light"
                                                    id="result_target_profit" readonly>
                                            </div>
                                            <div class="col-6">
                                                <label class="font-weight-bold">Gap to Target (%)</label>
                                                <input type="text" class="form-control bg-warning font-weight-bold"
                                                    id="result_profit_gap" readonly>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <label class="font-weight-bold">Required Cost Reduction (Rp)</label>
                                                <input type="text"
                                                    class="form-control bg-danger text-white font-weight-bold"
                                                    id="result_required_reduction" readonly>
                                                <small class="text-muted">Amount to reduce from Total Product Cost to
                                                    achieve target</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- DM Cost Section -->
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="font-weight-bold">Target Total DM Cost (Rp)</label>
                                    <input type="text" class="form-control bg-light font-weight-bold"
                                        id="target_dm_result" readonly>
                                    <small class="text-muted">Required DM cost for target profit</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="font-weight-bold">Existing Total DM Cost (Rp)</label>
                                    <input type="text" class="form-control bg-light" id="existing_dm_result" readonly>
                                    <small class="text-muted">Current DM cost from selected OPIN</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="font-weight-bold">Need Reduce DM (Rp)</label>
                                    <input type="text" class="form-control bg-warning font-weight-bold"
                                        id="gap_result" readonly>
                                    <small class="text-muted">Difference between target and existing</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="font-weight-bold">Need Reduce DM (%)</label>
                                    <input type="text" class="form-control bg-warning font-weight-bold"
                                        id="gap_percentage_result" readonly>
                                    <small class="text-muted">Percentage difference</small>
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-info">
                            <strong>Note:</strong> The calculation shows both the overall cost reduction needed and the
                            specific DM cost adjustment required to achieve your target profit margin.
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

        function calculateFromPercentage() {
            const salesPrice = parseFormattedNumber(document.getElementById('sales_price').value);
            const targetProfitPercentage = parseFloat(document.getElementById('target_profit_percentage').value);

            if (salesPrice && targetProfitPercentage) {
                const requiredRatio = (targetProfitPercentage / 100) + 1;
                const requiredTotalCost = salesPrice / requiredRatio;
                const profitAmount = salesPrice - requiredTotalCost;

                document.getElementById('target_profit_amount').value = formatNumber(profitAmount);
            }
        }

        function calculateFromAmount() {
            const salesPrice = parseFormattedNumber(document.getElementById('sales_price').value);
            const targetProfitAmount = parseFormattedNumber(document.getElementById('target_profit_amount').value);

            if (salesPrice && targetProfitAmount) {
                const requiredTotalCost = salesPrice - targetProfitAmount;
                if (requiredTotalCost > 0) {
                    const profitPercentage = ((salesPrice / requiredTotalCost) - 1) * 100;
                    document.getElementById('target_profit_percentage').value = profitPercentage.toFixed(2);
                } else {
                    alert('Target profit amount cannot be greater than or equal to sales price');
                    document.getElementById('target_profit_amount').value = '';
                    document.getElementById('target_profit_percentage').value = '';
                }
            }
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

                // Load SG&A percentage
                const sgaPercentage = parseFloat(selectedOption.getAttribute('data-sg-a-percentage')) || 0.0655;
                document.getElementById('sg_a_percentage').value = (sgaPercentage * 100).toFixed(2);

                // Calculate and display existing DM cost
                const rmCost = parseFloat(selectedOption.getAttribute('data-rm-cost')) || 0;
                const ckdCost = parseFloat(selectedOption.getAttribute('data-ckd-cost')) || 0;
                const ipCost = parseFloat(selectedOption.getAttribute('data-ip-cost')) || 0;
                const lpCost = parseFloat(selectedOption.getAttribute('data-lp-cost')) || 0;
                const existingDmCost = rmCost + ckdCost + ipCost + lpCost;

                document.getElementById('existing_dm_result').value = formatNumber(existingDmCost);

                // Load Total Product Cost and Profit
                const totalProductCost = parseFloat(selectedOption.getAttribute('data-total-product-cost')) || 0;
                const profitPercentage = parseFloat(selectedOption.getAttribute('data-profit-percentage')) || 0;

                // Display in results section
                document.getElementById('result_sales_price').value = formatNumber(salesPrice);
                document.getElementById('result_total_product_cost').value = formatNumber(totalProductCost);

                const profitAmount = salesPrice - totalProductCost;
                document.getElementById('result_profit_amount').value = formatNumber(profitAmount);
                document.getElementById('result_profit_percentage').value = profitPercentage.toFixed(2) + '%';

                // Calculate GAP if target is already calculated
                const targetDmCost = parseFormattedNumber(document.getElementById('target_dm_result').value);
                if (targetDmCost > 0) {
                    const gap = targetDmCost - existingDmCost;
                    document.getElementById('gap_result').value = formatNumber(gap);

                    // Calculate and display GAP percentage
                    if (existingDmCost > 0) {
                        const gapPercentage = (gap / existingDmCost) * 100;
                        document.getElementById('gap_percentage_result').value = gapPercentage.toFixed(2) + '%';
                    } else {
                        document.getElementById('gap_percentage_result').value = 'N/A';
                    }
                }
            } else {
                // Clear existing DM cost if no OPIN selected
                document.getElementById('existing_dm_result').value = '';
                document.getElementById('gap_result').value = '';
                document.getElementById('gap_percentage_result').value = '';
                document.getElementById('result_sales_price').value = '';
                document.getElementById('result_total_product_cost').value = '';
                document.getElementById('result_profit_amount').value = '';
                document.getElementById('result_profit_percentage').value = '';
            }
        }

        function clearForm() {
            document.getElementById('targetForm').reset();
            document.getElementById('existingOpin').selectedIndex = 0;
            document.getElementById('results').style.display = 'none';
            document.getElementById('target_dm_result').value = '';
            document.getElementById('existing_dm_result').value = '';
            document.getElementById('gap_result').value = '';
            document.getElementById('gap_percentage_result').value = '';

            // Clear formatted values
            document.getElementById('sales_price').value = '';
            document.getElementById('target_profit_percentage').value = '';
            document.getElementById('target_profit_amount').value = '';
            document.getElementById('labor_cost').value = '0';
            document.getElementById('machine_cost').value = '0';
            document.getElementById('current_machine').value = '0';
            document.getElementById('other_fixed').value = '0';
            document.getElementById('defect_cost').value = '0';
            document.getElementById('sg_a_percentage').value = '6.55';

            // Clear result fields
            document.getElementById('result_sales_price').value = '';
            document.getElementById('result_total_product_cost').value = '';
            document.getElementById('result_profit_amount').value = '';
            document.getElementById('result_profit_percentage').value = '';
            document.getElementById('result_target_profit').value = '';
            document.getElementById('result_profit_gap').value = '';
            document.getElementById('result_required_reduction').value = '';
        }

        function calculateTarget() {
            const salesPrice = parseFormattedNumber(document.getElementById('sales_price').value);
            let targetProfit = parseFloat(document.getElementById('target_profit_percentage').value);
            const targetProfitAmount = parseFormattedNumber(document.getElementById('target_profit_amount').value);
            const laborCost = parseFormattedNumber(document.getElementById('labor_cost').value);
            const machineCost = parseFormattedNumber(document.getElementById('machine_cost').value);
            const currentMachine = parseFormattedNumber(document.getElementById('current_machine').value);
            const otherFixed = parseFormattedNumber(document.getElementById('other_fixed').value);
            const defectCost = parseFormattedNumber(document.getElementById('defect_cost').value);
            const sgaPercentage = parseFloat(document.getElementById('sg_a_percentage').value) / 100;

            if (!salesPrice) {
                alert('Please enter Sales Price');
                return;
            }

            // Check if user entered percentage or amount
            if (!targetProfit && !targetProfitAmount) {
                alert('Please enter Target Profit (%) or Target Profit (Rp)');
                return;
            }

            // If amount is entered, calculate percentage
            if (targetProfitAmount && !targetProfit) {
                const requiredTotalCost = salesPrice - targetProfitAmount;
                if (requiredTotalCost <= 0) {
                    alert('Target profit amount cannot be greater than or equal to sales price');
                    return;
                }
                targetProfit = ((salesPrice / requiredTotalCost) - 1) * 100;
            }

            // Calculate required total product cost for target profit
            const requiredRatio = (targetProfit / 100) + 1;
            const requiredTotalCost = salesPrice / requiredRatio;

            // Fixed costs
            const fixedCosts = laborCost + machineCost + currentMachine + otherFixed + defectCost;

            // SG&A (dynamic % of product cost without common cost, but we need to estimate)
            // This is approximate - we'll iterate to find the correct value
            let productCostWithoutCommon = requiredTotalCost - (requiredTotalCost * sgaPercentage) - ((salesPrice - 0) *
                0.04); // Approximate royalty
            let sga = productCostWithoutCommon * sgaPercentage;
            let royalty = (salesPrice - 0) * 0.04; // Approximate
            let totalCost = productCostWithoutCommon + sga + royalty;

            // Adjust for accuracy
            for (let i = 0; i < 10; i++) {
                productCostWithoutCommon = requiredTotalCost - sga - royalty;
                sga = productCostWithoutCommon * sgaPercentage;
                royalty = (salesPrice - 0) * 0.04; // Still approximate
                totalCost = productCostWithoutCommon + sga + royalty;
            }

            // Variable costs (Total DM - RM, CKD, IP, LP combined)
            const variableCosts = productCostWithoutCommon - fixedCosts;

            if (variableCosts < 0) {
                alert(
                    'Fixed costs are too high for the target profit. Please reduce fixed costs or increase target profit.'
                );
                return;
            }

            // Display target DM cost
            document.getElementById('target_dm_result').value = formatNumber(variableCosts);

            // Display Sales Price and required Total Product Cost
            document.getElementById('result_sales_price').value = formatNumber(salesPrice);
            document.getElementById('result_total_product_cost').value = formatNumber(requiredTotalCost);
            document.getElementById('result_target_profit').value = targetProfit.toFixed(2) + '%';

            // Calculate profit amount with required total cost
            const profitAmount = salesPrice - requiredTotalCost;
            document.getElementById('result_profit_amount').value = formatNumber(profitAmount);

            // Calculate current profit percentage if OPIN is selected
            const select = document.getElementById('existingOpin');
            const selectedOption = select.options[select.selectedIndex];

            if (selectedOption.value) {
                const currentTotalCost = parseFloat(selectedOption.getAttribute('data-total-product-cost')) || 0;
                const currentProfitPercentage = parseFloat(selectedOption.getAttribute('data-profit-percentage')) || 0;

                document.getElementById('result_profit_percentage').value = currentProfitPercentage.toFixed(2) + '%';

                // Calculate gap to target
                const profitGap = currentProfitPercentage - targetProfit;
                document.getElementById('result_profit_gap').value = profitGap.toFixed(2) + '%';

                // Calculate required cost reduction
                const requiredReduction = currentTotalCost - requiredTotalCost;
                document.getElementById('result_required_reduction').value = formatNumber(requiredReduction);
            } else {
                document.getElementById('result_profit_percentage').value = 'N/A';
                document.getElementById('result_profit_gap').value = 'Select OPIN';
                document.getElementById('result_required_reduction').value = 'Select OPIN';
            }

            // Calculate and display GAP
            const existingDmCost = parseFormattedNumber(document.getElementById('existing_dm_result').value);
            if (existingDmCost > 0) {
                const gap = variableCosts - existingDmCost;
                document.getElementById('gap_result').value = formatNumber(gap);

                // Calculate and display GAP percentage
                const gapPercentage = (gap / existingDmCost) * 100;
                document.getElementById('gap_percentage_result').value = gapPercentage.toFixed(2) + '%';
            } else {
                document.getElementById('gap_result').value = 'Select OPIN to calculate GAP';
                document.getElementById('gap_percentage_result').value = 'Select OPIN to calculate GAP';
            }

            document.getElementById('results').style.display = 'block';
        }
    </script>
@endsection
