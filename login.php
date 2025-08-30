<?php
session_start();

// If user is already logged in, redirect to dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'config.php';
    
    $user_id = trim($_POST['user_id']);
    $password = $_POST['password'];
    $role = $_POST['role'];
    
    if (!empty($user_id) && !empty($password) && !empty($role)) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ? AND role = ?");
            $stmt->execute([$user_id, $role]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                // Login successful
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['role'] = $user['role'];
                
                // Redirect based on role
                if ($role === 'admin') {
                    header('Location: admin_panel.php');
                } else {
                    header('Location: index.php');
                }
                exit();
            } else {
                $error_message = 'Invalid credentials. Please try again.';
            }
        } catch (PDOException $e) {
            $error_message = 'Database error. Please try again later.';
        }
    } else {
        $error_message = 'Please fill in all fields.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MeatChain Pro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <div class="login-card p-5">
                        <div class="text-center mb-4">
                            <i class="bi bi-truck display-4 text-primary mb-3"></i>
                            <h2 class="fw-bold">MeatChain Pro</h2>
                            <p class="text-muted">Sign in to your account</p>
                        </div>

                        <?php if ($error_message): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <?php echo htmlspecialchars($error_message); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="user_id" class="form-label fw-semibold">
                                    <i class="bi bi-person me-2"></i>User ID
                                </label>
                                <input type="text" class="form-control form-control-lg" id="user_id" name="user_id" 
                                       placeholder="Enter your user ID" required 
                                       value="<?php echo isset($_POST['user_id']) ? htmlspecialchars($_POST['user_id']) : ''; ?>">
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold">
                                    <i class="bi bi-lock me-2"></i>Password
                                </label>
                                <input type="password" class="form-control form-control-lg" id="password" name="password" 
                                       placeholder="Enter your password" required>
                            </div>
                            
                            <div class="mb-4">
                                <label for="role" class="form-label fw-semibold">
                                    <i class="bi bi-person-badge me-2"></i>Select Role
                                </label>
                                <select class="form-select form-select-lg" id="role" name="role" required>
                                    <option value="">Choose your role</option>
                                    <option value="admin" <?php echo (isset($_POST['role']) && $_POST['role'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
                                    <option value="customer" <?php echo (isset($_POST['role']) && $_POST['role'] === 'customer') ? 'selected' : ''; ?>>Customer</option>
                                </select>
                            </div>
                            
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary btn-lg fw-semibold">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                                </button>
                            </div>
                        </form>
                        
                        <div class="text-center">
                            <p class="text-muted mb-2">Don't have an account?</p>
                            <a href="signup.php" class="btn btn-outline-primary">
                                <i class="bi bi-person-plus me-2"></i>Create Account
                            </a>
                        </div>

                        <div class="mt-4 pt-4 border-top">
                            <h6 class="text-center text-muted mb-3">Demo Credentials</h6>
                            <div class="row text-center">
                                <div class="col-6">
                                    <small class="text-muted">
                                        <strong>Admin:</strong><br>
                                        ID: admin<br>
                                        Pass: password
                                    </small>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">
                                        <strong>Customer:</strong><br>
                                        ID: customer<br>
                                        Pass: password
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
