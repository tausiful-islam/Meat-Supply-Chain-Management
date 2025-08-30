<?php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Meat Production Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
        }
        .card {
            background: rgba(255, 255, 255, 0.95);
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
            transition: transform 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .admin-card {
            height: 180px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-decoration: none;
            color: inherit;
            cursor: pointer;
        }
        .admin-card:hover {
            color: inherit;
            text-decoration: none;
        }
        .admin-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #667eea;
        }
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
        }
        .btn-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 25px;
            padding: 10px 30px;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            color: white;
        }
        .feature-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #28a745;
            color: white;
            border-radius: 15px;
            padding: 3px 8px;
            font-size: 0.7rem;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">
                <i class="fas fa-chart-line me-2"></i>
                Meat Production Analytics
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="admin_panel.php">Admin Panel</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="reports.php">Reports</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container my-5">
        <!-- Welcome Section -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="card text-center">
                    <div class="card-body py-4">
                        <h1 class="display-5 fw-bold mb-3">
                            <i class="fas fa-cog me-3"></i>Admin Control Panel
                        </h1>
                        <p class="lead">Comprehensive management system for meat production data</p>
                        <div class="row mt-4">
                            <div class="col-md-3">
                                <div class="stats-card card">
                                    <div class="card-body text-center">
                                        <i class="fas fa-database fa-2x mb-2"></i>
                                        <h5>6 Tables</h5>
                                        <p class="mb-0">Database Management</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stats-card card">
                                    <div class="card-body text-center">
                                        <i class="fas fa-chart-bar fa-2x mb-2"></i>
                                        <h5>Live Charts</h5>
                                        <p class="mb-0">Real-time Analytics</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stats-card card">
                                    <div class="card-body text-center">
                                        <i class="fas fa-edit fa-2x mb-2"></i>
                                        <h5>CRUD Operations</h5>
                                        <p class="mb-0">Full Data Control</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stats-card card">
                                    <div class="card-body text-center">
                                        <i class="fas fa-shield-alt fa-2x mb-2"></i>
                                        <h5>Secure Access</h5>
                                        <p class="mb-0">Protected Management</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Management Features -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0"><i class="fas fa-tools me-2"></i>Data Management Features</h4>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <!-- Feature 1: Meat Products Data -->
                            <div class="col-lg-4 col-md-6">
                                <div class="card admin-card position-relative" onclick="location.href='meat_products.php'">
                                    <div class="feature-badge">TABLE</div>
                                    <i class="fas fa-drumstick-bite admin-icon"></i>
                                    <h5 class="fw-bold">Meat Products Data</h5>
                                    <p class="text-muted text-center">Manage specifications & breeds</p>
                                    <small class="text-success">✓ Insert, Edit, Delete</small>
                                </div>
                            </div>

                            <!-- Feature 2: Production Records -->
                            <div class="col-lg-4 col-md-6">
                                <div class="card admin-card position-relative" onclick="location.href='production_records.php'">
                                    <div class="feature-badge">TABLE + CHARTS</div>
                                    <i class="fas fa-industry admin-icon"></i>
                                    <h5 class="fw-bold">Production Records</h5>
                                    <p class="text-muted text-center">Track production with analytics</p>
                                    <small class="text-success">✓ Charts, Insert, Delete</small>
                                </div>
                            </div>

                            <!-- Feature 3: Price Analysis -->
                            <div class="col-lg-4 col-md-6">
                                <div class="card admin-card position-relative" onclick="location.href='price_analysis.php'">
                                    <div class="feature-badge">CHARTS + TABLE</div>
                                    <i class="fas fa-dollar-sign admin-icon"></i>
                                    <h5 class="fw-bold">Price Analysis</h5>
                                    <p class="text-muted text-center">Market trends & pricing</p>
                                    <small class="text-success">✓ 4 Charts, Insert, Delete</small>
                                </div>
                            </div>

                            <!-- Feature 4: Consumption Insights -->
                            <div class="col-lg-4 col-md-6">
                                <div class="card admin-card position-relative" onclick="location.href='consumption_insights.php'">
                                    <div class="feature-badge">CHARTS + TABLE</div>
                                    <i class="fas fa-chart-pie admin-icon"></i>
                                    <h5 class="fw-bold">Consumption Insights</h5>
                                    <p class="text-muted text-center">Consumption & nutrition data</p>
                                    <small class="text-success">✓ Regional Charts, Insert, Delete</small>
                                </div>
                            </div>

                            <!-- Feature 5: Demand Elasticity -->
                            <div class="col-lg-4 col-md-6">
                                <div class="card admin-card position-relative" onclick="location.href='demand_elasticity.php'">
                                    <div class="feature-badge">TABLE</div>
                                    <i class="fas fa-balance-scale admin-icon"></i>
                                    <h5 class="fw-bold">Demand Elasticity</h5>
                                    <p class="text-muted text-center">Price-demand correlations</p>
                                    <small class="text-success">✓ Analysis Tools, Insert, Edit, Delete</small>
                                </div>
                            </div>

                            <!-- Feature 6: Supply vs Demand -->
                            <div class="col-lg-4 col-md-6">
                                <div class="card admin-card position-relative" onclick="location.href='supply_demand.php'">
                                    <div class="feature-badge">CHARTS + TABLE</div>
                                    <i class="fas fa-exchange-alt admin-icon"></i>
                                    <h5 class="fw-bold">Supply vs Demand</h5>
                                    <p class="text-muted text-center">Assessment & forecasting</p>
                                    <small class="text-success">✓ 4 Charts, Insert, Delete</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Database Management Tools -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0"><i class="fas fa-database me-2"></i>Database Management Tools</h4>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="card text-center h-100">
                                    <div class="card-body d-flex flex-column justify-content-center">
                                        <i class="fas fa-server fa-2x text-primary mb-3"></i>
                                        <h5>phpMyAdmin Access</h5>
                                        <p class="text-muted">Direct database management</p>
                                        <a href="http://localhost/phpmyadmin" target="_blank" class="btn btn-outline-primary">
                                            <i class="fas fa-external-link-alt me-2"></i>Open phpMyAdmin
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-center h-100">
                                    <div class="card-body d-flex flex-column justify-content-center">
                                        <i class="fas fa-download fa-2x text-success mb-3"></i>
                                        <h5>Database Backup</h5>
                                        <p class="text-muted">Export complete database</p>
                                        <button class="btn btn-outline-success" onclick="backupDatabase()">
                                            <i class="fas fa-download me-2"></i>Backup Database
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-center h-100">
                                    <div class="card-body d-flex flex-column justify-content-center">
                                        <i class="fas fa-chart-line fa-2x text-info mb-3"></i>
                                        <h5>System Statistics</h5>
                                        <p class="text-muted">Database performance metrics</p>
                                        <button class="btn btn-outline-info" onclick="showStats()">
                                            <i class="fas fa-chart-bar me-2"></i>View Statistics
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h4>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <button class="btn btn-custom w-100 py-3" onclick="location.href='index.php'">
                                    <i class="fas fa-home d-block mb-2 fa-2x"></i>
                                    <span>Dashboard</span>
                                </button>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-outline-primary w-100 py-3" onclick="location.href='reports.php'">
                                    <i class="fas fa-file-pdf d-block mb-2 fa-2x"></i>
                                    <span>Generate Reports</span>
                                </button>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-outline-success w-100 py-3" onclick="refreshAllData()">
                                    <i class="fas fa-sync-alt d-block mb-2 fa-2x"></i>
                                    <span>Refresh Data</span>
                                </button>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-outline-warning w-100 py-3" onclick="showHelp()">
                                    <i class="fas fa-question-circle d-block mb-2 fa-2x"></i>
                                    <span>Help & Guide</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Help Modal -->
    <div class="modal fade" id="helpModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-question-circle me-2"></i>Admin Panel Guide</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <h6><i class="fas fa-drumstick-bite me-2"></i>Meat Products Data (TABLE FORMAT)</h6>
                    <ul>
                        <li>Insert new meat product specifications</li>
                        <li>Edit existing breed and production data</li>
                        <li>Delete outdated records</li>
                        <li>Search and filter by meat type</li>
                    </ul>

                    <h6><i class="fas fa-industry me-2"></i>Production Records (TABLE + CHARTS)</h6>
                    <ul>
                        <li>View production trends by district</li>
                        <li>Compare livestock counts by division</li>
                        <li>Track meat yield distribution</li>
                        <li>Add/delete production records</li>
                    </ul>

                    <h6><i class="fas fa-dollar-sign me-2"></i>Price Analysis (CHARTS + TABLE)</h6>
                    <ul>
                        <li>Wholesale vs retail price trends</li>
                        <li>Seasonal price fluctuation analysis</li>
                        <li>Regional price comparison</li>
                        <li>Historical price data management</li>
                    </ul>

                    <h6><i class="fas fa-chart-pie me-2"></i>Consumption Insights (CHARTS + TABLE)</h6>
                    <ul>
                        <li>Regional consumption comparisons</li>
                        <li>Demographic consumption patterns</li>
                        <li>Nutritional intake analysis</li>
                        <li>Population-based consumption data</li>
                    </ul>

                    <h6><i class="fas fa-balance-scale me-2"></i>Demand Elasticity (TABLE FORMAT)</h6>
                    <ul>
                        <li>Price-demand correlation analysis</li>
                        <li>Cross-elasticity calculations</li>
                        <li>Alternative protein impact studies</li>
                        <li>Market sensitivity assessments</li>
                    </ul>

                    <h6><i class="fas fa-exchange-alt me-2"></i>Supply vs Demand (CHARTS + TABLE)</h6>
                    <ul>
                        <li>Supply-demand comparison by region</li>
                        <li>Surplus/deficit trend analysis</li>
                        <li>Policy recommendation tracking</li>
                        <li>Market balance assessments</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-custom" data-bs-dismiss="modal">Got it!</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Modal -->
    <div class="modal fade" id="statsModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-chart-bar me-2"></i>System Statistics</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="statsContent">
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading statistics...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        function backupDatabase() {
            if (confirm('This will create a backup of the complete database. Continue?')) {
                window.open('backup_database.php', '_blank');
            }
        }

        function showStats() {
            $('#statsModal').modal('show');
            
            // Load statistics
            $.get('get_stats.php', function(data) {
                if (data.success) {
                    const stats = data.stats;
                    const content = `
                        <div class="row text-center">
                            <div class="col-md-6 mb-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <h4>${stats.meat_products || 0}</h4>
                                        <p class="mb-0">Meat Products</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <h4>${stats.production_records || 0}</h4>
                                        <p class="mb-0">Production Records</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body">
                                        <h4>${stats.price_history || 0}</h4>
                                        <p class="mb-0">Price Records</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card bg-warning text-white">
                                    <div class="card-body">
                                        <h4>${stats.consumption_data || 0}</h4>
                                        <p class="mb-0">Consumption Data</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card bg-secondary text-white">
                                    <div class="card-body">
                                        <h4>${stats.demand_elasticity || 0}</h4>
                                        <p class="mb-0">Elasticity Analyses</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card bg-dark text-white">
                                    <div class="card-body">
                                        <h4>${stats.supply_demand_analysis || 0}</h4>
                                        <p class="mb-0">Supply-Demand Analyses</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    $('#statsContent').html(content);
                } else {
                    $('#statsContent').html('<div class="alert alert-danger">Error loading statistics</div>');
                }
            }, 'json').fail(function() {
                $('#statsContent').html('<div class="alert alert-danger">Failed to load statistics</div>');
            });
        }

        function refreshAllData() {
            if (confirm('This will refresh all data connections. Continue?')) {
                location.reload();
            }
        }

        function showHelp() {
            $('#helpModal').modal('show');
        }
    </script>
</body>
</html>
