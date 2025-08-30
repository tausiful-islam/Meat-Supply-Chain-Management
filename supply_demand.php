<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

require_once 'config.php';

// Handle form submissions
$message = '';
$messageType = '';

if ($_POST) {
    try {
        if (isset($_POST['action'])) {
            switch ($_POST['action']) {
                case 'add':
                    $sql = "INSERT INTO supply_demand_analysis (region, meat_type, supply_quantity_tons, demand_quantity_tons, surplus_deficit_tons, analysis_month, policy_recommendation) 
                            VALUES (:region, :meat_type, :supply_quantity_tons, :demand_quantity_tons, :surplus_deficit_tons, :analysis_month, :policy_recommendation)";
                    
                    // Calculate surplus/deficit
                    $surplus_deficit = $_POST['supply_quantity_tons'] - $_POST['demand_quantity_tons'];
                    
                    $params = [
                        'region' => $_POST['region'],
                        'meat_type' => $_POST['meat_type'],
                        'supply_quantity_tons' => $_POST['supply_quantity_tons'],
                        'demand_quantity_tons' => $_POST['demand_quantity_tons'],
                        'surplus_deficit_tons' => $surplus_deficit,
                        'analysis_month' => $_POST['analysis_month'],
                        'policy_recommendation' => $_POST['policy_recommendation']
                    ];
                    executeQuery($pdo, $sql, $params);
                    $message = "Supply-demand analysis added successfully!";
                    $messageType = "success";
                    break;
                    
                case 'delete':
                    deleteRecord($pdo, 'supply_demand_analysis', $_POST['id']);
                    $message = "Supply-demand analysis deleted successfully!";
                    $messageType = "success";
                    break;
            }
        }
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
        $messageType = "danger";
    }
}

// Get all supply-demand records
$records = getAllRecords($pdo, 'supply_demand_analysis');

// Get data for charts
$chartData = [];
try {
    // Supply vs Demand by region
    $stmt = $pdo->prepare("SELECT region, AVG(supply_quantity_tons) as avg_supply, AVG(demand_quantity_tons) as avg_demand FROM supply_demand_analysis GROUP BY region");
    $stmt->execute();
    $chartData['regional_comparison'] = $stmt->fetchAll();
    
    // Surplus/Deficit analysis
    $stmt = $pdo->prepare("SELECT region, AVG(surplus_deficit_tons) as avg_surplus_deficit FROM supply_demand_analysis GROUP BY region ORDER BY avg_surplus_deficit DESC");
    $stmt->execute();
    $chartData['surplus_deficit'] = $stmt->fetchAll();
    
    // Supply-demand by meat type
    $stmt = $pdo->prepare("SELECT meat_type, AVG(supply_quantity_tons) as avg_supply, AVG(demand_quantity_tons) as avg_demand FROM supply_demand_analysis GROUP BY meat_type");
    $stmt->execute();
    $chartData['meat_type_analysis'] = $stmt->fetchAll();
    
    // Monthly trends
    $stmt = $pdo->prepare("SELECT DATE_FORMAT(analysis_month, '%Y-%m') as month, AVG(surplus_deficit_tons) as avg_surplus_deficit FROM supply_demand_analysis GROUP BY month ORDER BY month");
    $stmt->execute();
    $chartData['monthly_trends'] = $stmt->fetchAll();
    
} catch (Exception $e) {
    $chartData = ['regional_comparison' => [], 'surplus_deficit' => [], 'meat_type_analysis' => [], 'monthly_trends' => []];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supply vs Demand Assessment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .card {
            background: rgba(255, 255, 255, 0.95);
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
        }
        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
        }
        .btn-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
        }
        .btn-custom:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            color: white;
        }
        .chart-container {
            position: relative;
            height: 400px;
        }
        .surplus {
            color: #28a745;
            font-weight: bold;
        }
        .deficit {
            color: #dc3545;
            font-weight: bold;
        }
        .balanced {
            color: #6c757d;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">
                <i class="fas fa-chart-line me-2"></i>Meat Production Analytics
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.php">Dashboard</a>
                <a class="nav-link" href="admin_panel.php">Admin Panel</a>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center">
                        <h2 class="card-title"><i class="fas fa-exchange-alt me-2"></i>Supply vs Demand Assessment</h2>
                        <p class="card-text">Comprehensive supply-demand analysis and forecasting</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        <?php if ($message): ?>
        <div class="alert alert-<?= $messageType ?> alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($message) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <!-- Charts Section -->
        <div class="row mb-4">
            <div class="col-lg-6 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Regional Supply vs Demand</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="regionalChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Surplus/Deficit Analysis</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="surplusDeficitChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-lg-6 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Supply-Demand by Meat Type</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="meatTypeChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Monthly Surplus/Deficit Trends</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="monthlyTrendsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add New Analysis Form -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-plus me-2"></i>Add New Supply-Demand Analysis</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" class="row g-3" id="analysisForm">
                            <input type="hidden" name="action" value="add">
                            <div class="col-md-3">
                                <label for="region" class="form-label">Region</label>
                                <input type="text" class="form-control" name="region" required>
                            </div>
                            <div class="col-md-3">
                                <label for="meat_type" class="form-label">Meat Type</label>
                                <select class="form-select" name="meat_type" required>
                                    <option value="">Select Type</option>
                                    <option value="Beef">Beef</option>
                                    <option value="Chicken">Chicken</option>
                                    <option value="Mutton">Mutton</option>
                                    <option value="Goat">Goat</option>
                                    <option value="Duck">Duck</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="analysis_month" class="form-label">Analysis Month</label>
                                <input type="month" class="form-control" name="analysis_month" required>
                            </div>
                            <div class="col-md-3">
                                <label for="supply_quantity_tons" class="form-label">Supply Quantity (tons)</label>
                                <input type="number" step="0.01" class="form-control" name="supply_quantity_tons" id="supplyQuantity" required>
                            </div>
                            <div class="col-md-4">
                                <label for="demand_quantity_tons" class="form-label">Demand Quantity (tons)</label>
                                <input type="number" step="0.01" class="form-control" name="demand_quantity_tons" id="demandQuantity" required>
                            </div>
                            <div class="col-md-4">
                                <label for="surplus_deficit_display" class="form-label">Surplus/Deficit (tons)</label>
                                <input type="text" class="form-control" id="surplusDeficitDisplay" readonly>
                                <small class="text-muted">Calculated automatically</small>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">&nbsp;</label>
                                <button type="button" class="btn btn-outline-primary d-block" onclick="calculateSurplusDeficit()">
                                    <i class="fas fa-calculator me-2"></i>Calculate
                                </button>
                            </div>
                            <div class="col-12">
                                <label for="policy_recommendation" class="form-label">Policy Recommendation</label>
                                <textarea class="form-control" name="policy_recommendation" rows="3" placeholder="Enter policy recommendations based on analysis..."></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-custom">
                                    <i class="fas fa-plus me-2"></i>Add Analysis
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Analysis Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-table me-2"></i>Supply-Demand Analysis Database</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="analysisTable" class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Region</th>
                                        <th>Meat Type</th>
                                        <th>Supply (tons)</th>
                                        <th>Demand (tons)</th>
                                        <th>Surplus/Deficit</th>
                                        <th>Analysis Month</th>
                                        <th>Policy Recommendation</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($records as $record): ?>
                                    <?php 
                                        $surplusDeficit = $record['surplus_deficit_tons'];
                                        $statusClass = $surplusDeficit > 0 ? 'surplus' : ($surplusDeficit < 0 ? 'deficit' : 'balanced');
                                        $statusText = $surplusDeficit > 0 ? 'Surplus' : ($surplusDeficit < 0 ? 'Deficit' : 'Balanced');
                                    ?>
                                    <tr>
                                        <td><?= $record['id'] ?></td>
                                        <td><?= htmlspecialchars($record['region']) ?></td>
                                        <td><span class="badge bg-primary"><?= htmlspecialchars($record['meat_type']) ?></span></td>
                                        <td><?= number_format($record['supply_quantity_tons'], 2) ?></td>
                                        <td><?= number_format($record['demand_quantity_tons'], 2) ?></td>
                                        <td>
                                            <span class="<?= $statusClass ?>">
                                                <?= number_format(abs($record['surplus_deficit_tons']), 2) ?> 
                                                (<?= $statusText ?>)
                                            </span>
                                        </td>
                                        <td><?= date('M Y', strtotime($record['analysis_month'])) ?></td>
                                        <td>
                                            <span class="text-truncate d-inline-block" style="max-width: 200px;" 
                                                  title="<?= htmlspecialchars($record['policy_recommendation']) ?>">
                                                <?= htmlspecialchars(substr($record['policy_recommendation'], 0, 50)) ?>...
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-danger" onclick="deleteRecord(<?= $record['id'] ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#analysisTable').DataTable({
                responsive: true,
                pageLength: 10,
                order: [[0, 'desc']]
            });
            
            // Initialize charts
            initializeCharts();
        });

        function calculateSurplusDeficit() {
            const supply = parseFloat(document.getElementById('supplyQuantity').value) || 0;
            const demand = parseFloat(document.getElementById('demandQuantity').value) || 0;
            const surplusDeficit = supply - demand;
            
            document.getElementById('surplusDeficitDisplay').value = surplusDeficit.toFixed(2) + 
                (surplusDeficit > 0 ? ' (Surplus)' : surplusDeficit < 0 ? ' (Deficit)' : ' (Balanced)');
        }

        function initializeCharts() {
            // Regional Comparison Chart
            const regionalData = <?= json_encode($chartData['regional_comparison']) ?>;
            const regionalChart = new Chart(document.getElementById('regionalChart'), {
                type: 'bar',
                data: {
                    labels: regionalData.map(item => item.region),
                    datasets: [{
                        label: 'Supply (tons)',
                        data: regionalData.map(item => item.avg_supply),
                        backgroundColor: 'rgba(54, 162, 235, 0.8)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }, {
                        label: 'Demand (tons)',
                        data: regionalData.map(item => item.avg_demand),
                        backgroundColor: 'rgba(255, 99, 132, 0.8)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Surplus/Deficit Chart
            const surplusData = <?= json_encode($chartData['surplus_deficit']) ?>;
            const surplusDeficitChart = new Chart(document.getElementById('surplusDeficitChart'), {
                type: 'bar',
                data: {
                    labels: surplusData.map(item => item.region),
                    datasets: [{
                        label: 'Surplus/Deficit (tons)',
                        data: surplusData.map(item => item.avg_surplus_deficit),
                        backgroundColor: surplusData.map(item => 
                            item.avg_surplus_deficit > 0 ? 'rgba(40, 167, 69, 0.8)' : 'rgba(220, 53, 69, 0.8)'
                        ),
                        borderColor: surplusData.map(item => 
                            item.avg_surplus_deficit > 0 ? 'rgba(40, 167, 69, 1)' : 'rgba(220, 53, 69, 1)'
                        ),
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Meat Type Analysis Chart
            const meatTypeData = <?= json_encode($chartData['meat_type_analysis']) ?>;
            const meatTypeChart = new Chart(document.getElementById('meatTypeChart'), {
                type: 'radar',
                data: {
                    labels: meatTypeData.map(item => item.meat_type),
                    datasets: [{
                        label: 'Supply',
                        data: meatTypeData.map(item => item.avg_supply),
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: 'rgba(54, 162, 235, 1)'
                    }, {
                        label: 'Demand',
                        data: meatTypeData.map(item => item.avg_demand),
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        pointBackgroundColor: 'rgba(255, 99, 132, 1)',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: 'rgba(255, 99, 132, 1)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

            // Monthly Trends Chart
            const monthlyData = <?= json_encode($chartData['monthly_trends']) ?>;
            const monthlyTrendsChart = new Chart(document.getElementById('monthlyTrendsChart'), {
                type: 'line',
                data: {
                    labels: monthlyData.map(item => item.month),
                    datasets: [{
                        label: 'Surplus/Deficit Trend (tons)',
                        data: monthlyData.map(item => item.avg_surplus_deficit),
                        borderColor: 'rgba(102, 126, 234, 1)',
                        backgroundColor: 'rgba(102, 126, 234, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        function deleteRecord(id) {
            if (confirm('Are you sure you want to delete this supply-demand analysis?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="${id}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Auto-calculate surplus/deficit when supply or demand changes
        document.getElementById('supplyQuantity').addEventListener('input', calculateSurplusDeficit);
        document.getElementById('demandQuantity').addEventListener('input', calculateSurplusDeficit);
    </script>
</body>
</html>
