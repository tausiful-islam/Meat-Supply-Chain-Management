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
                    $sql = "INSERT INTO production_records (district, division, livestock_count, slaughter_rate, meat_yield_kg, production_date, meat_type) 
                            VALUES (:district, :division, :livestock_count, :slaughter_rate, :meat_yield_kg, :production_date, :meat_type)";
                    $params = [
                        'district' => $_POST['district'],
                        'division' => $_POST['division'],
                        'livestock_count' => $_POST['livestock_count'],
                        'slaughter_rate' => $_POST['slaughter_rate'],
                        'meat_yield_kg' => $_POST['meat_yield_kg'],
                        'production_date' => $_POST['production_date'],
                        'meat_type' => $_POST['meat_type']
                    ];
                    executeQuery($pdo, $sql, $params);
                    $message = "Production record added successfully!";
                    $messageType = "success";
                    break;
                    
                case 'delete':
                    deleteRecord($pdo, 'production_records', $_POST['id']);
                    $message = "Production record deleted successfully!";
                    $messageType = "success";
                    break;
            }
        }
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
        $messageType = "danger";
    }
}

// Get all production records
$records = getAllRecords($pdo, 'production_records');

// Get data for charts
$chartData = [];
try {
    // Production trends by district
    $stmt = $pdo->prepare("SELECT district, SUM(meat_yield_kg) as total_yield FROM production_records GROUP BY district ORDER BY total_yield DESC");
    $stmt->execute();
    $chartData['districts'] = $stmt->fetchAll();
    
    // Production by meat type
    $stmt = $pdo->prepare("SELECT meat_type, SUM(meat_yield_kg) as total_yield FROM production_records GROUP BY meat_type ORDER BY total_yield DESC");
    $stmt->execute();
    $chartData['meat_types'] = $stmt->fetchAll();
    
    // Monthly production trends
    $stmt = $pdo->prepare("SELECT DATE_FORMAT(production_date, '%Y-%m') as month, SUM(meat_yield_kg) as total_yield FROM production_records GROUP BY month ORDER BY month");
    $stmt->execute();
    $chartData['monthly'] = $stmt->fetchAll();
    
} catch (Exception $e) {
    $chartData = ['districts' => [], 'meat_types' => [], 'monthly' => []];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Production Records Management</title>
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
                        <h2 class="card-title"><i class="fas fa-industry me-2"></i>Production Records Management</h2>
                        <p class="card-text">Track production data with comprehensive visual analytics</p>
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
                        <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Production by District</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="districtChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Production by Meat Type</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="meatTypeChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Monthly Production Trends</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="monthlyChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add New Record Form -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-plus me-2"></i>Add New Production Record</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" class="row g-3">
                            <input type="hidden" name="action" value="add">
                            <div class="col-md-3">
                                <label for="district" class="form-label">District</label>
                                <input type="text" class="form-control" name="district" required>
                            </div>
                            <div class="col-md-3">
                                <label for="division" class="form-label">Division</label>
                                <input type="text" class="form-control" name="division" required>
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
                                <label for="production_date" class="form-label">Production Date</label>
                                <input type="date" class="form-control" name="production_date" required>
                            </div>
                            <div class="col-md-4">
                                <label for="livestock_count" class="form-label">Livestock Count</label>
                                <input type="number" class="form-control" name="livestock_count" required>
                            </div>
                            <div class="col-md-4">
                                <label for="slaughter_rate" class="form-label">Slaughter Rate (%)</label>
                                <input type="number" step="0.01" class="form-control" name="slaughter_rate" required>
                            </div>
                            <div class="col-md-4">
                                <label for="meat_yield_kg" class="form-label">Meat Yield (kg)</label>
                                <input type="number" step="0.01" class="form-control" name="meat_yield_kg" required>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-custom">
                                    <i class="fas fa-plus me-2"></i>Add Record
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Records Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-table me-2"></i>Production Records Database</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="recordsTable" class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>District</th>
                                        <th>Division</th>
                                        <th>Meat Type</th>
                                        <th>Livestock Count</th>
                                        <th>Slaughter Rate (%)</th>
                                        <th>Meat Yield (kg)</th>
                                        <th>Production Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($records as $record): ?>
                                    <tr>
                                        <td><?= $record['id'] ?></td>
                                        <td><?= htmlspecialchars($record['district']) ?></td>
                                        <td><?= htmlspecialchars($record['division']) ?></td>
                                        <td><span class="badge bg-primary"><?= htmlspecialchars($record['meat_type']) ?></span></td>
                                        <td><?= number_format($record['livestock_count']) ?></td>
                                        <td><?= number_format($record['slaughter_rate'], 2) ?>%</td>
                                        <td><?= number_format($record['meat_yield_kg'], 2) ?></td>
                                        <td><?= date('M d, Y', strtotime($record['production_date'])) ?></td>
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
            $('#recordsTable').DataTable({
                responsive: true,
                pageLength: 10,
                order: [[0, 'desc']]
            });
            
            // Initialize charts
            initializeCharts();
        });

        function initializeCharts() {
            // District Chart
            const districtData = <?= json_encode($chartData['districts']) ?>;
            const districtChart = new Chart(document.getElementById('districtChart'), {
                type: 'bar',
                data: {
                    labels: districtData.map(item => item.district),
                    datasets: [{
                        label: 'Total Yield (kg)',
                        data: districtData.map(item => item.total_yield),
                        backgroundColor: 'rgba(102, 126, 234, 0.8)',
                        borderColor: 'rgba(102, 126, 234, 1)',
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

            // Meat Type Chart
            const meatTypeData = <?= json_encode($chartData['meat_types']) ?>;
            const meatTypeChart = new Chart(document.getElementById('meatTypeChart'), {
                type: 'pie',
                data: {
                    labels: meatTypeData.map(item => item.meat_type),
                    datasets: [{
                        data: meatTypeData.map(item => item.total_yield),
                        backgroundColor: [
                            '#FF6384',
                            '#36A2EB',
                            '#FFCE56',
                            '#4BC0C0',
                            '#9966FF',
                            '#FF9F40'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

            // Monthly Trends Chart
            const monthlyData = <?= json_encode($chartData['monthly']) ?>;
            const monthlyChart = new Chart(document.getElementById('monthlyChart'), {
                type: 'line',
                data: {
                    labels: monthlyData.map(item => item.month),
                    datasets: [{
                        label: 'Monthly Production (kg)',
                        data: monthlyData.map(item => item.total_yield),
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
            if (confirm('Are you sure you want to delete this production record?')) {
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
