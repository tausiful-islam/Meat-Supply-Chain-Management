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
                    $sql = "INSERT INTO price_history (meat_type, wholesale_price, retail_price, district, division, price_date, seasonal_factor) 
                            VALUES (:meat_type, :wholesale_price, :retail_price, :district, :division, :price_date, :seasonal_factor)";
                    $params = [
                        'meat_type' => $_POST['meat_type'],
                        'wholesale_price' => $_POST['wholesale_price'],
                        'retail_price' => $_POST['retail_price'],
                        'district' => $_POST['district'],
                        'division' => $_POST['division'],
                        'price_date' => $_POST['price_date'],
                        'seasonal_factor' => $_POST['seasonal_factor']
                    ];
                    executeQuery($pdo, $sql, $params);
                    $message = "Price record added successfully!";
                    $messageType = "success";
                    break;
                    
                case 'delete':
                    deleteRecord($pdo, 'price_history', $_POST['id']);
                    $message = "Price record deleted successfully!";
                    $messageType = "success";
                    break;
            }
        }
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
        $messageType = "danger";
    }
}

// Get all price records
$records = getAllRecords($pdo, 'price_history');

// Get data for charts
$chartData = [];
try {
    // Price trends by meat type
    $stmt = $pdo->prepare("SELECT meat_type, AVG(wholesale_price) as avg_wholesale, AVG(retail_price) as avg_retail FROM price_history GROUP BY meat_type");
    $stmt->execute();
    $chartData['price_comparison'] = $stmt->fetchAll();
    
    // Seasonal price fluctuations
    $stmt = $pdo->prepare("SELECT seasonal_factor, AVG(retail_price) as avg_price FROM price_history GROUP BY seasonal_factor");
    $stmt->execute();
    $chartData['seasonal'] = $stmt->fetchAll();
    
    // Regional price comparison
    $stmt = $pdo->prepare("SELECT division, AVG(retail_price) as avg_price FROM price_history GROUP BY division ORDER BY avg_price DESC");
    $stmt->execute();
    $chartData['regional'] = $stmt->fetchAll();
    
    // Monthly price trends
    $stmt = $pdo->prepare("SELECT DATE_FORMAT(price_date, '%Y-%m') as month, AVG(retail_price) as avg_price FROM price_history GROUP BY month ORDER BY month");
    $stmt->execute();
    $chartData['monthly_trends'] = $stmt->fetchAll();
    
} catch (Exception $e) {
    $chartData = ['price_comparison' => [], 'seasonal' => [], 'regional' => [], 'monthly_trends' => []];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Price Analysis Management</title>
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
                        <h2 class="card-title"><i class="fas fa-dollar-sign me-2"></i>Price Analysis Management</h2>
                        <p class="card-text">Analyze price trends and market data with comprehensive charts</p>
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
                        <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Wholesale vs Retail Prices</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="priceComparisonChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Seasonal Price Fluctuations</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="seasonalChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-lg-6 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-map-marked-alt me-2"></i>Regional Price Comparison</h5>
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
                        <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Monthly Price Trends</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="monthlyTrendsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add New Price Record Form -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-plus me-2"></i>Add New Price Record</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" class="row g-3">
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
                                <label for="district" class="form-label">District</label>
                                <input type="text" class="form-control" name="district" required>
                            </div>
                            <div class="col-md-3">
                                <label for="division" class="form-label">Division</label>
                                <input type="text" class="form-control" name="division" required>
                            </div>
                            <div class="col-md-3">
                                <label for="price_date" class="form-label">Price Date</label>
                                <input type="date" class="form-control" name="price_date" required>
                            </div>
                            <div class="col-md-3">
                                <label for="wholesale_price" class="form-label">Wholesale Price (৳)</label>
                                <input type="number" step="0.01" class="form-control" name="wholesale_price" required>
                            </div>
                            <div class="col-md-3">
                                <label for="retail_price" class="form-label">Retail Price (৳)</label>
                                <input type="number" step="0.01" class="form-control" name="retail_price" required>
                            </div>
                            <div class="col-md-3">
                                <label for="seasonal_factor" class="form-label">Seasonal Factor</label>
                                <select class="form-select" name="seasonal_factor" required>
                                    <option value="">Select Season</option>
                                    <option value="summer">Summer</option>
                                    <option value="winter">Winter</option>
                                    <option value="monsoon">Monsoon</option>
                                    <option value="spring">Spring</option>
                                    <option value="autumn">Autumn</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn btn-custom d-block">
                                    <i class="fas fa-plus me-2"></i>Add Price Record
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Price History Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-table me-2"></i>Price History Database</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="priceTable" class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Meat Type</th>
                                        <th>District</th>
                                        <th>Division</th>
                                        <th>Wholesale Price</th>
                                        <th>Retail Price</th>
                                        <th>Price Date</th>
                                        <th>Season</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($records as $record): ?>
                                    <tr>
                                        <td><?= $record['id'] ?></td>
                                        <td><span class="badge bg-primary"><?= htmlspecialchars($record['meat_type']) ?></span></td>
                                        <td><?= htmlspecialchars($record['district']) ?></td>
                                        <td><?= htmlspecialchars($record['division']) ?></td>
                                        <td>৳<?= number_format($record['wholesale_price'], 2) ?></td>
                                        <td>৳<?= number_format($record['retail_price'], 2) ?></td>
                                        <td><?= date('M d, Y', strtotime($record['price_date'])) ?></td>
                                        <td><span class="badge bg-info"><?= htmlspecialchars($record['seasonal_factor']) ?></span></td>
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
            $('#priceTable').DataTable({
                responsive: true,
                pageLength: 10,
                order: [[0, 'desc']]
            });
            
            // Initialize charts
            initializeCharts();
        });

        function initializeCharts() {
            // Price Comparison Chart
            const priceData = <?= json_encode($chartData['price_comparison']) ?>;
            const priceComparisonChart = new Chart(document.getElementById('priceComparisonChart'), {
                type: 'bar',
                data: {
                    labels: priceData.map(item => item.meat_type),
                    datasets: [{
                        label: 'Wholesale Price (৳)',
                        data: priceData.map(item => item.avg_wholesale),
                        backgroundColor: 'rgba(54, 162, 235, 0.8)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }, {
                        label: 'Retail Price (৳)',
                        data: priceData.map(item => item.avg_retail),
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

            // Seasonal Chart
            const seasonalData = <?= json_encode($chartData['seasonal']) ?>;
            const seasonalChart = new Chart(document.getElementById('seasonalChart'), {
                type: 'pie',
                data: {
                    labels: seasonalData.map(item => item.seasonal_factor),
                    datasets: [{
                        data: seasonalData.map(item => item.avg_price),
                        backgroundColor: [
                            '#FF6384',
                            '#36A2EB',
                            '#FFCE56',
                            '#4BC0C0',
                            '#9966FF'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

            // Regional Chart
            const regionalData = <?= json_encode($chartData['regional']) ?>;
            const regionalChart = new Chart(document.getElementById('regionalChart'), {
                type: 'bar',
                data: {
                    labels: regionalData.map(item => item.division),
                    datasets: [{
                        label: 'Average Price (৳)',
                        data: regionalData.map(item => item.avg_price),
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

            // Monthly Trends Chart
            const monthlyData = <?= json_encode($chartData['monthly_trends']) ?>;
            const monthlyTrendsChart = new Chart(document.getElementById('monthlyTrendsChart'), {
                type: 'line',
                data: {
                    labels: monthlyData.map(item => item.month),
                    datasets: [{
                        label: 'Average Price (৳)',
                        data: monthlyData.map(item => item.avg_price),
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
            if (confirm('Are you sure you want to delete this price record?')) {
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
