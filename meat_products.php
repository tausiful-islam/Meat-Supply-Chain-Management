<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
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
                    $sql = "INSERT INTO meat_products (meat_type, breed, average_weight_slaughter, feed_conversion_ratio, typical_rearing_period_days) 
                            VALUES (:meat_type, :breed, :weight, :ratio, :days)";
                    $params = [
                        'meat_type' => $_POST['meat_type'],
                        'breed' => $_POST['breed'],
                        'weight' => $_POST['average_weight_slaughter'],
                        'ratio' => $_POST['feed_conversion_ratio'],
                        'days' => $_POST['typical_rearing_period_days']
                    ];
                    executeQuery($pdo, $sql, $params);
                    $message = "Meat product added successfully!";
                    $messageType = "success";
                    break;
                    
                case 'edit':
                    $sql = "UPDATE meat_products SET meat_type = :meat_type, breed = :breed, 
                            average_weight_slaughter = :weight, feed_conversion_ratio = :ratio, 
                            typical_rearing_period_days = :days, updated_at = CURRENT_TIMESTAMP 
                            WHERE id = :id";
                    $params = [
                        'id' => $_POST['id'],
                        'meat_type' => $_POST['meat_type'],
                        'breed' => $_POST['breed'],
                        'weight' => $_POST['average_weight_slaughter'],
                        'ratio' => $_POST['feed_conversion_ratio'],
                        'days' => $_POST['typical_rearing_period_days']
                    ];
                    executeQuery($pdo, $sql, $params);
                    $message = "Meat product updated successfully!";
                    $messageType = "success";
                    break;
                    
                case 'delete':
                    deleteRecord($pdo, 'meat_products', $_POST['id']);
                    $message = "Meat product deleted successfully!";
                    $messageType = "success";
                    break;
            }
        }
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
        $messageType = "danger";
    }
}

// Get all meat products
$products = getAllRecords($pdo, 'meat_products');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meat Products Data Management</title>
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
                        <h2 class="card-title"><i class="fas fa-drumstick-bite me-2"></i>Meat Products Data Management</h2>
                        <p class="card-text">Manage meat specifications, breeds, and production parameters</p>
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

        <!-- Add New Product Form -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-plus me-2"></i>Add New Meat Product</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" class="row g-3">
                            <input type="hidden" name="action" value="add">
                            <div class="col-md-4">
                                <label for="meat_type" class="form-label">Meat Type</label>
                                <select class="form-select" name="meat_type" required>
                                    <option value="">Select Type</option>
                                    <option value="Beef">Beef</option>
                                    <option value="Chicken">Chicken</option>
                                    <option value="Mutton">Mutton</option>
                                    <option value="Goat">Goat</option>
                                    <option value="Duck">Duck</option>
                                    <option value="Fish">Fish</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="breed" class="form-label">Breed</label>
                                <input type="text" class="form-control" name="breed" required>
                            </div>
                            <div class="col-md-4">
                                <label for="average_weight_slaughter" class="form-label">Average Weight (kg)</label>
                                <input type="number" step="0.01" class="form-control" name="average_weight_slaughter" required>
                            </div>
                            <div class="col-md-6">
                                <label for="feed_conversion_ratio" class="form-label">Feed Conversion Ratio</label>
                                <input type="number" step="0.01" class="form-control" name="feed_conversion_ratio" required>
                            </div>
                            <div class="col-md-6">
                                <label for="typical_rearing_period_days" class="form-label">Rearing Period (days)</label>
                                <input type="number" class="form-control" name="typical_rearing_period_days" required>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-custom">
                                    <i class="fas fa-plus me-2"></i>Add Product
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-table me-2"></i>Meat Products Database</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="productsTable" class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Meat Type</th>
                                        <th>Breed</th>
                                        <th>Avg Weight (kg)</th>
                                        <th>Feed Ratio</th>
                                        <th>Rearing Period (days)</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($products as $product): ?>
                                    <tr>
                                        <td><?= $product['id'] ?></td>
                                        <td><span class="badge bg-primary"><?= htmlspecialchars($product['meat_type']) ?></span></td>
                                        <td><?= htmlspecialchars($product['breed']) ?></td>
                                        <td><?= number_format($product['average_weight_slaughter'], 2) ?></td>
                                        <td><?= number_format($product['feed_conversion_ratio'], 2) ?></td>
                                        <td><?= $product['typical_rearing_period_days'] ?></td>
                                        <td><?= date('M d, Y', strtotime($product['created_at'])) ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-warning" onclick="editProduct(<?= $product['id'] ?>)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" onclick="deleteProduct(<?= $product['id'] ?>)">
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
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Meat Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="editForm">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="id" id="editId">
                        <div class="mb-3">
                            <label for="editMeatType" class="form-label">Meat Type</label>
                            <select class="form-select" name="meat_type" id="editMeatType" required>
                                <option value="Beef">Beef</option>
                                <option value="Chicken">Chicken</option>
                                <option value="Mutton">Mutton</option>
                                <option value="Goat">Goat</option>
                                <option value="Duck">Duck</option>
                                <option value="Fish">Fish</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editBreed" class="form-label">Breed</label>
                            <input type="text" class="form-control" name="breed" id="editBreed" required>
                        </div>
                        <div class="mb-3">
                            <label for="editWeight" class="form-label">Average Weight (kg)</label>
                            <input type="number" step="0.01" class="form-control" name="average_weight_slaughter" id="editWeight" required>
                        </div>
                        <div class="mb-3">
                            <label for="editRatio" class="form-label">Feed Conversion Ratio</label>
                            <input type="number" step="0.01" class="form-control" name="feed_conversion_ratio" id="editRatio" required>
                        </div>
                        <div class="mb-3">
                            <label for="editDays" class="form-label">Rearing Period (days)</label>
                            <input type="number" class="form-control" name="typical_rearing_period_days" id="editDays" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-custom">Update Product</button>
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
            $('#productsTable').DataTable({
                responsive: true,
                pageLength: 10,
                order: [[0, 'desc']]
            });
        });

        function editProduct(id) {
            // Get product data
            $.get('get_product.php?id=' + id, function(data) {
                if (data.success) {
                    const product = data.product;
                    $('#editId').val(product.id);
                    $('#editMeatType').val(product.meat_type);
                    $('#editBreed').val(product.breed);
                    $('#editWeight').val(product.average_weight_slaughter);
                    $('#editRatio').val(product.feed_conversion_ratio);
                    $('#editDays').val(product.typical_rearing_period_days);
                    $('#editModal').modal('show');
                }
            }, 'json');
        }

        function deleteProduct(id) {
            if (confirm('Are you sure you want to delete this product?')) {
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
