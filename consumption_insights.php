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
                    $sql = "INSERT INTO consumption_data (region, demographic_group, per_capita_consumption_kg, nutritional_intake_protein_g, nutritional_intake_calories, survey_year, population_size) 
                            VALUES (:region, :demographic_group, :per_capita_consumption_kg, :nutritional_intake_protein_g, :nutritional_intake_calories, :survey_year, :population_size)";
                    $params = [
                        'region' => $_POST['region'],
                        'demographic_group' => $_POST['demographic_group'],
                        'per_capita_consumption_kg' => $_POST['per_capita_consumption_kg'],
                        'nutritional_intake_protein_g' => $_POST['nutritional_intake_protein_g'],
                        'nutritional_intake_calories' => $_POST['nutritional_intake_calories'],
                        'survey_year' => $_POST['survey_year'],
                        'population_size' => $_POST['population_size']
                    ];
                    executeQuery($pdo, $sql, $params);
                    $message = "Consumption record added successfully!";
                    $messageType = "success";
                    break;
                    
                case 'delete':
                    deleteRecord($pdo, 'consumption_data', $_POST['id']);
                    $message = "Consumption record deleted successfully!";
                    $messageType = "success";
                    break;
            }
        }
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
        $messageType = "danger";
    }
}

// Get all consumption records
$records = getAllRecords($pdo, 'consumption_data');

// Get data for charts
$chartData = [];
try {
    // Regional consumption comparison
    $stmt = $pdo->prepare("SELECT region, AVG(per_capita_consumption_kg) as avg_consumption FROM consumption_data GROUP BY region ORDER BY avg_consumption DESC");
    $stmt->execute();
    $chartData['regional'] = $stmt->fetchAll();
    
    // Demographic consumption patterns
    $stmt = $pdo->prepare("SELECT demographic_group, AVG(per_capita_consumption_kg) as avg_consumption FROM consumption_data GROUP BY demographic_group");
    $stmt->execute();
    $chartData['demographic'] = $stmt->fetchAll();
    
    // Nutritional intake analysis
    $stmt = $pdo->prepare("SELECT region, AVG(nutritional_intake_protein_g) as avg_protein, AVG(nutritional_intake_calories) as avg_calories FROM consumption_data GROUP BY region");
    $stmt->execute();
    $chartData['nutrition'] = $stmt->fetchAll();
    
    // Yearly consumption trends
    $stmt = $pdo->prepare("SELECT survey_year, AVG(per_capita_consumption_kg) as avg_consumption FROM consumption_data GROUP BY survey_year ORDER BY survey_year");
    $stmt->execute();
    $chartData['yearly_trends'] = $stmt->fetchAll();
    
} catch (Exception $e) {
    $chartData = ['regional' => [], 'demographic' => [], 'nutrition' => [], 'yearly_trends' => []];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consumption Insights Management</title>
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
                        <h2 class="card-title"><i class="fas fa-chart-pie me-2"></i>Consumption Insights Management</h2>
                        <p class="card-text">Analyze consumption patterns and nutritional data</p>
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
                        <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Regional Consumption Comparison</h5>
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
                        <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Demographic Consumption Patterns</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="demographicChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-lg-6 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-apple-alt me-2"></i>Nutritional Intake Analysis</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="nutritionChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Yearly Consumption Trends</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="yearlyTrendsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add New Consumption Record Form -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-plus me-2"></i>Add New Consumption Record</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" class="row g-3">
                            <input type="hidden" name="action" value="add">
                            <div class="col-md-3">
                                <label for="region" class="form-label">Region</label>
                                <input type="text" class="form-control" name="region" required>
                            </div>
                            <div class="col-md-3">
                                <label for="demographic_group" class="form-label">Demographic Group</label>
                                <select class="form-select" name="demographic_group" required>
                                    <option value="">Select Group</option>
                                    <option value="urban">Urban</option>
                                    <option value="rural">Rural</option>
                                    <option value="income_bracket_high">High Income</option>
                                    <option value="income_bracket_medium">Medium Income</option>
                                    <option value="income_bracket_low">Low Income</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="survey_year" class="form-label">Survey Year</label>
                                <input type="number" class="form-control" name="survey_year" min="2020" max="2030" required>
                            </div>
                            <div class="col-md-3">
                                <label for="population_size" class="form-label">Population Size</label>
                                <input type="number" class="form-control" name="population_size" required>
                            </div>
                            <div class="col-md-4">
                                <label for="per_capita_consumption_kg" class="form-label">Per Capita Consumption (kg)</label>
                                <input type="number" step="0.01" class="form-control" name="per_capita_consumption_kg" required>
                            </div>
                            <div class="col-md-4">
                                <label for="nutritional_intake_protein_g" class="form-label">Protein Intake (g)</label>
                                <input type="number" step="0.01" class="form-control" name="nutritional_intake_protein_g" required>
                            </div>
                            <div class="col-md-4">
                                <label for="nutritional_intake_calories" class="form-label">Calorie Intake</label>
                                <input type="number" step="0.01" class="form-control" name="nutritional_intake_calories" required>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-custom">
                                    <i class="fas fa-plus me-2"></i>Add Consumption Record
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Consumption Data Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-table me-2"></i>Consumption Data Database</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="consumptionTable" class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Region</th>
                                        <th>Demographic</th>
                                        <th>Per Capita (kg)</th>
                                        <th>Protein (g)</th>
                                        <th>Calories</th>
                                        <th>Survey Year</th>
                                        <th>Population</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($records as $record): ?>
                                    <tr>
                                        <td><?= $record['id'] ?></td>
                                        <td><?= htmlspecialchars($record['region']) ?></td>
                                        <td><span class="badge bg-info"><?= htmlspecialchars($record['demographic_group']) ?></span></td>
                                        <td><?= number_format($record['per_capita_consumption_kg'], 2) ?></td>
                                        <td><?= number_format($record['nutritional_intake_protein_g'], 2) ?></td>
                                        <td><?= number_format($record['nutritional_intake_calories'], 0) ?></td>
                                        <td><?= $record['survey_year'] ?></td>
                                        <td><?= number_format($record['population_size']) ?></td>
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
            $('#consumptionTable').DataTable({
                responsive: true,
                pageLength: 10,
                order: [[0, 'desc']]
            });
            
            // Initialize charts
            initializeCharts();
        });

        function initializeCharts() {
            // Regional Chart
            const regionalData = <?= json_encode($chartData['regional']) ?>;
            const regionalChart = new Chart(document.getElementById('regionalChart'), {
                type: 'bar',
                data: {
                    labels: regionalData.map(item => item.region),
                    datasets: [{
                        label: 'Average Consumption (kg)',
                        data: regionalData.map(item => item.avg_consumption),
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

            // Demographic Chart
            const demographicData = <?= json_encode($chartData['demographic']) ?>;
            const demographicChart = new Chart(document.getElementById('demographicChart'), {
                type: 'pie',
                data: {
                    labels: demographicData.map(item => item.demographic_group),
                    datasets: [{
                        data: demographicData.map(item => item.avg_consumption),
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

            // Nutrition Chart
            const nutritionData = <?= json_encode($chartData['nutrition']) ?>;
            const nutritionChart = new Chart(document.getElementById('nutritionChart'), {
                type: 'bar',
                data: {
                    labels: nutritionData.map(item => item.region),
                    datasets: [{
                        label: 'Protein (g)',
                        data: nutritionData.map(item => item.avg_protein),
                        backgroundColor: 'rgba(54, 162, 235, 0.8)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1,
                        yAxisID: 'y'
                    }, {
                        label: 'Calories',
                        data: nutritionData.map(item => item.avg_calories),
                        backgroundColor: 'rgba(255, 99, 132, 0.8)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1,
                        yAxisID: 'y1'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            grid: {
                                drawOnChartArea: false,
                            },
                        },
                    }
                }
            });

            // Yearly Trends Chart
            const yearlyData = <?= json_encode($chartData['yearly_trends']) ?>;
            const yearlyTrendsChart = new Chart(document.getElementById('yearlyTrendsChart'), {
                type: 'line',
                data: {
                    labels: yearlyData.map(item => item.survey_year),
                    datasets: [{
                        label: 'Average Consumption (kg)',
                        data: yearlyData.map(item => item.avg_consumption),
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
            if (confirm('Are you sure you want to delete this consumption record?')) {
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
