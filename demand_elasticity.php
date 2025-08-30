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
                    $sql = "INSERT INTO demand_elasticity (meat_type, price_change_percent, demand_change_percent, alternative_protein, cross_elasticity_value, analysis_period, region) 
                            VALUES (:meat_type, :price_change_percent, :demand_change_percent, :alternative_protein, :cross_elasticity_value, :analysis_period, :region)";
                    $params = [
                        'meat_type' => $_POST['meat_type'],
                        'price_change_percent' => $_POST['price_change_percent'],
                        'demand_change_percent' => $_POST['demand_change_percent'],
                        'alternative_protein' => $_POST['alternative_protein'],
                        'cross_elasticity_value' => $_POST['cross_elasticity_value'],
                        'analysis_period' => $_POST['analysis_period'],
                        'region' => $_POST['region']
                    ];
                    executeQuery($pdo, $sql, $params);
                    $message = "Demand elasticity analysis added successfully!";
                    $messageType = "success";
                    break;
                    
                case 'edit':
                    $sql = "UPDATE demand_elasticity SET meat_type = :meat_type, price_change_percent = :price_change_percent, 
                            demand_change_percent = :demand_change_percent, alternative_protein = :alternative_protein, 
                            cross_elasticity_value = :cross_elasticity_value, analysis_period = :analysis_period, region = :region 
                            WHERE id = :id";
                    $params = [
                        'id' => $_POST['id'],
                        'meat_type' => $_POST['meat_type'],
                        'price_change_percent' => $_POST['price_change_percent'],
                        'demand_change_percent' => $_POST['demand_change_percent'],
                        'alternative_protein' => $_POST['alternative_protein'],
                        'cross_elasticity_value' => $_POST['cross_elasticity_value'],
                        'analysis_period' => $_POST['analysis_period'],
                        'region' => $_POST['region']
                    ];
                    executeQuery($pdo, $sql, $params);
                    $message = "Demand elasticity analysis updated successfully!";
                    $messageType = "success";
                    break;
                    
                case 'delete':
                    deleteRecord($pdo, 'demand_elasticity', $_POST['id']);
                    $message = "Demand elasticity analysis deleted successfully!";
                    $messageType = "success";
                    break;
            }
        }
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
        $messageType = "danger";
    }
}

// Get all demand elasticity records
$records = getAllRecords($pdo, 'demand_elasticity');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demand Elasticity Analysis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
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
        .elasticity-positive {
            color: #28a745;
            font-weight: bold;
        }
        .elasticity-negative {
            color: #dc3545;
            font-weight: bold;
        }
        .elasticity-neutral {
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
                        <h2 class="card-title"><i class="fas fa-balance-scale me-2"></i>Demand Elasticity Analysis</h2>
                        <p class="card-text">Price-demand analysis and correlation studies</p>
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

        <!-- Elasticity Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-chart-line fa-2x text-primary mb-2"></i>
                        <h5>Price Elasticity</h5>
                        <p class="text-muted">Measures demand sensitivity to price changes</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-exchange-alt fa-2x text-success mb-2"></i>
                        <h5>Cross Elasticity</h5>
                        <p class="text-muted">Alternative protein substitution effects</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-calculator fa-2x text-warning mb-2"></i>
                        <h5>Correlation Analysis</h5>
                        <p class="text-muted">Statistical relationship measurements</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-chart-pie fa-2x text-info mb-2"></i>
                        <h5>Market Insights</h5>
                        <p class="text-muted">Regional demand patterns</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add New Analysis Form -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-plus me-2"></i>Add New Elasticity Analysis</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" class="row g-3" id="elasticityForm">
                            <input type="hidden" name="action" value="add">
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
                                <label for="region" class="form-label">Region</label>
                                <input type="text" class="form-control" name="region" required>
                            </div>
                            <div class="col-md-3">
                                <label for="analysis_period" class="form-label">Analysis Period</label>
                                <select class="form-select" name="analysis_period" required>
                                    <option value="">Select Period</option>
                                    <option value="2024-Q1">2024 Q1</option>
                                    <option value="2024-Q2">2024 Q2</option>
                                    <option value="2024-Q3">2024 Q3</option>
                                    <option value="2024-Q4">2024 Q4</option>
                                    <option value="2024-Annual">2024 Annual</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="alternative_protein" class="form-label">Alternative Protein</label>
                                <select class="form-select" name="alternative_protein" required>
                                    <option value="">Select Alternative</option>
                                    <option value="fish">Fish</option>
                                    <option value="eggs">Eggs</option>
                                    <option value="dairy">Dairy</option>
                                    <option value="plant-based">Plant-based</option>
                                    <option value="pulses">Pulses</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="price_change_percent" class="form-label">Price Change (%)</label>
                                <input type="number" step="0.01" class="form-control" name="price_change_percent" id="priceChange" required>
                                <small class="text-muted">Positive for increase, negative for decrease</small>
                            </div>
                            <div class="col-md-4">
                                <label for="demand_change_percent" class="form-label">Demand Change (%)</label>
                                <input type="number" step="0.01" class="form-control" name="demand_change_percent" id="demandChange" required>
                                <small class="text-muted">Positive for increase, negative for decrease</small>
                            </div>
                            <div class="col-md-4">
                                <label for="cross_elasticity_value" class="form-label">Cross Elasticity Value</label>
                                <input type="number" step="0.001" class="form-control" name="cross_elasticity_value" required>
                                <small class="text-muted">Calculated automatically or manual entry</small>
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-outline-primary" onclick="calculateElasticity()">
                                            <i class="fas fa-calculator me-2"></i>Calculate Elasticity
                                        </button>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <button type="submit" class="btn btn-custom">
                                            <i class="fas fa-plus me-2"></i>Add Analysis
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Elasticity Analysis Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-table me-2"></i>Demand Elasticity Database</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="elasticityTable" class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Meat Type</th>
                                        <th>Region</th>
                                        <th>Period</th>
                                        <th>Price Change</th>
                                        <th>Demand Change</th>
                                        <th>Price Elasticity</th>
                                        <th>Alternative</th>
                                        <th>Cross Elasticity</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($records as $record): ?>
                                    <?php 
                                        $priceElasticity = $record['price_change_percent'] != 0 ? $record['demand_change_percent'] / $record['price_change_percent'] : 0;
                                        $elasticityClass = $priceElasticity > 0 ? 'elasticity-positive' : ($priceElasticity < 0 ? 'elasticity-negative' : 'elasticity-neutral');
                                    ?>
                                    <tr>
                                        <td><?= $record['id'] ?></td>
                                        <td><span class="badge bg-primary"><?= htmlspecialchars($record['meat_type']) ?></span></td>
                                        <td><?= htmlspecialchars($record['region']) ?></td>
                                        <td><?= htmlspecialchars($record['analysis_period']) ?></td>
                                        <td><span class="<?= $record['price_change_percent'] >= 0 ? 'text-danger' : 'text-success' ?>"><?= number_format($record['price_change_percent'], 2) ?>%</span></td>
                                        <td><span class="<?= $record['demand_change_percent'] >= 0 ? 'text-success' : 'text-danger' ?>"><?= number_format($record['demand_change_percent'], 2) ?>%</span></td>
                                        <td><span class="<?= $elasticityClass ?>"><?= number_format($priceElasticity, 3) ?></span></td>
                                        <td><span class="badge bg-info"><?= htmlspecialchars($record['alternative_protein']) ?></span></td>
                                        <td><?= number_format($record['cross_elasticity_value'], 3) ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-warning" onclick="editAnalysis(<?= $record['id'] ?>)">
                                                <i class="fas fa-edit"></i>
                                            </button>
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

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Elasticity Analysis</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="editForm">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="id" id="editId">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="editMeatType" class="form-label">Meat Type</label>
                                <select class="form-select" name="meat_type" id="editMeatType" required>
                                    <option value="Beef">Beef</option>
                                    <option value="Chicken">Chicken</option>
                                    <option value="Mutton">Mutton</option>
                                    <option value="Goat">Goat</option>
                                    <option value="Duck">Duck</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="editRegion" class="form-label">Region</label>
                                <input type="text" class="form-control" name="region" id="editRegion" required>
                            </div>
                            <div class="col-md-6">
                                <label for="editPeriod" class="form-label">Analysis Period</label>
                                <select class="form-select" name="analysis_period" id="editPeriod" required>
                                    <option value="2024-Q1">2024 Q1</option>
                                    <option value="2024-Q2">2024 Q2</option>
                                    <option value="2024-Q3">2024 Q3</option>
                                    <option value="2024-Q4">2024 Q4</option>
                                    <option value="2024-Annual">2024 Annual</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="editAlternative" class="form-label">Alternative Protein</label>
                                <select class="form-select" name="alternative_protein" id="editAlternative" required>
                                    <option value="fish">Fish</option>
                                    <option value="eggs">Eggs</option>
                                    <option value="dairy">Dairy</option>
                                    <option value="plant-based">Plant-based</option>
                                    <option value="pulses">Pulses</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="editPriceChange" class="form-label">Price Change (%)</label>
                                <input type="number" step="0.01" class="form-control" name="price_change_percent" id="editPriceChange" required>
                            </div>
                            <div class="col-md-4">
                                <label for="editDemandChange" class="form-label">Demand Change (%)</label>
                                <input type="number" step="0.01" class="form-control" name="demand_change_percent" id="editDemandChange" required>
                            </div>
                            <div class="col-md-4">
                                <label for="editCrossElasticity" class="form-label">Cross Elasticity</label>
                                <input type="number" step="0.001" class="form-control" name="cross_elasticity_value" id="editCrossElasticity" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-custom">Update Analysis</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#elasticityTable').DataTable({
                responsive: true,
                pageLength: 10,
                order: [[0, 'desc']]
            });
        });

        function calculateElasticity() {
            const priceChange = parseFloat(document.getElementById('priceChange').value);
            const demandChange = parseFloat(document.getElementById('demandChange').value);
            
            if (priceChange && demandChange && priceChange !== 0) {
                const elasticity = demandChange / priceChange;
                alert(`Calculated Price Elasticity: ${elasticity.toFixed(3)}\n\n` +
                      `Interpretation:\n` +
                      `${elasticity < -1 ? 'Elastic demand (sensitive to price changes)' : 
                        elasticity > -1 && elasticity < 0 ? 'Inelastic demand (less sensitive to price changes)' :
                        'Positive elasticity (unusual for normal goods)'}`);
            } else {
                alert('Please enter valid price change and demand change values.');
            }
        }

        function editAnalysis(id) {
            // Get analysis data - you would implement get_elasticity.php similar to get_product.php
            $.get('get_elasticity.php?id=' + id, function(data) {
                if (data.success) {
                    const analysis = data.analysis;
                    $('#editId').val(analysis.id);
                    $('#editMeatType').val(analysis.meat_type);
                    $('#editRegion').val(analysis.region);
                    $('#editPeriod').val(analysis.analysis_period);
                    $('#editAlternative').val(analysis.alternative_protein);
                    $('#editPriceChange').val(analysis.price_change_percent);
                    $('#editDemandChange').val(analysis.demand_change_percent);
                    $('#editCrossElasticity').val(analysis.cross_elasticity_value);
                    $('#editModal').modal('show');
                }
            }, 'json');
        }

        function deleteRecord(id) {
            if (confirm('Are you sure you want to delete this elasticity analysis?')) {
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
    </script>
</body>
</html>
