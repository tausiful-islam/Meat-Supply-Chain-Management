<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meat Production Data Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
        .feature-card {
            height: 200px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-decoration: none;
            color: inherit;
        }
        .feature-card:hover {
            color: inherit;
            text-decoration: none;
        }
        .feature-icon {
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
                        <a class="nav-link active" href="index.php">Dashboard</a>
                    </li>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="admin_panel.php">Admin Panel</a>
                    </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="reports.php">Reports</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i><?php echo htmlspecialchars($_SESSION['name']); ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><span class="dropdown-item-text text-muted">Role: <?php echo ucfirst($_SESSION['role']); ?></span></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                        </ul>
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
                    <div class="card-body py-5">
                        <h1 class="display-4 fw-bold mb-3">Meat Production Data Management</h1>
                        <p class="lead">Comprehensive analytics and management system for meat production data</p>
                        <a href="admin_panel.php" class="btn btn-custom btn-lg">
                            <i class="fas fa-cog me-2"></i>Access Admin Panel
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Overview -->
        <div class="row mb-5">
            <div class="col-md-3 mb-3">
                <div class="card stats-card">
                    <div class="card-body text-center">
                        <i class="fas fa-drumstick-bite fa-2x mb-2"></i>
                        <h4 id="totalProducts">-</h4>
                        <p class="mb-0">Meat Products</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stats-card">
                    <div class="card-body text-center">
                        <i class="fas fa-industry fa-2x mb-2"></i>
                        <h4 id="totalProduction">-</h4>
                        <p class="mb-0">Production Records</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stats-card">
                    <div class="card-body text-center">
                        <i class="fas fa-chart-line fa-2x mb-2"></i>
                        <h4 id="totalPriceRecords">-</h4>
                        <p class="mb-0">Price Records</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stats-card">
                    <div class="card-body text-center">
                        <i class="fas fa-users fa-2x mb-2"></i>
                        <h4 id="totalConsumption">-</h4>
                        <p class="mb-0">Consumption Data</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Feature Cards -->
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <a href="meat_products.php" class="text-decoration-none">
                    <div class="card feature-card">
                        <i class="fas fa-drumstick-bite feature-icon"></i>
                        <h5 class="fw-bold">Meat Products Data</h5>
                        <p class="text-muted text-center">Manage meat specifications and breeding data</p>
                    </div>
                </a>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <a href="production_records.php" class="text-decoration-none">
                    <div class="card feature-card">
                        <i class="fas fa-industry feature-icon"></i>
                        <h5 class="fw-bold">Production Records</h5>
                        <p class="text-muted text-center">Track production data with visual analytics</p>
                    </div>
                </a>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <a href="price_analysis.php" class="text-decoration-none">
                    <div class="card feature-card">
                        <i class="fas fa-dollar-sign feature-icon"></i>
                        <h5 class="fw-bold">Price Analysis</h5>
                        <p class="text-muted text-center">Analyze price trends and market data</p>
                    </div>
                </a>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <a href="consumption_insights.php" class="text-decoration-none">
                    <div class="card feature-card">
                        <i class="fas fa-chart-pie feature-icon"></i>
                        <h5 class="fw-bold">Consumption Insights</h5>
                        <p class="text-muted text-center">Consumption patterns and nutritional data</p>
                    </div>
                </a>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <a href="demand_elasticity.php" class="text-decoration-none">
                    <div class="card feature-card">
                        <i class="fas fa-balance-scale feature-icon"></i>
                        <h5 class="fw-bold">Demand Elasticity</h5>
                        <p class="text-muted text-center">Price-demand analysis and correlations</p>
                    </div>
                </a>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <a href="supply_demand.php" class="text-decoration-none">
                    <div class="card feature-card">
                        <i class="fas fa-exchange-alt feature-icon"></i>
                        <h5 class="fw-bold">Supply vs Demand</h5>
                        <p class="text-muted text-center">Supply-demand assessment and forecasting</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Load dashboard statistics
        $(document).ready(function() {
            loadDashboardStats();
        });

        function loadDashboardStats() {
            // Load stats from each table
            $.get('get_stats.php', function(data) {
                if (data.success) {
                    $('#totalProducts').text(data.stats.meat_products || 0);
                    $('#totalProduction').text(data.stats.production_records || 0);
                    $('#totalPriceRecords').text(data.stats.price_history || 0);
                    $('#totalConsumption').text(data.stats.consumption_data || 0);
                }
            }, 'json').fail(function() {
                // Set default values if AJAX fails
                $('#totalProducts, #totalProduction, #totalPriceRecords, #totalConsumption').text('N/A');
            });
        }
    </script>
</body>
</html>
