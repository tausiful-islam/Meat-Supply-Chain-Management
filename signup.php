<?php
session_start();

// If user is already logged in, redirect to dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'config.php';
    
    $name = trim($_POST['name']);
    $user_id = trim($_POST['user_id']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];
    
    // Validation
    if (empty($name) || empty($user_id) || empty($password) || empty($confirm_password) || empty($role)) {
        $error_message = 'Please fill in all fields.';
    } elseif ($password !== $confirm_password) {
        $error_message = 'Passwords do not match.';
    } elseif (strlen($password) < 8) {
        $error_message = 'Password must be at least 8 characters long.';
    } elseif (!preg_match('/^(?=.*[A-Z])(?=.*\d)/', $password)) {
        $error_message = 'Password must contain at least one uppercase letter and one number.';
    } else {
        try {
            // Check if user_id already exists
            $stmt = $pdo->prepare("SELECT user_id FROM users WHERE user_id = ?");
            $stmt->execute([$user_id]);
            
            if ($stmt->fetch()) {
                $error_message = 'User ID already exists. Please choose a different one.';
            } else {
                // Create new user
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (user_id, name, password, role) VALUES (?, ?, ?, ?)");
                
                if ($stmt->execute([$user_id, $name, $hashed_password, $role])) {
                    $success_message = 'Account created successfully! You can now sign in.';
                } else {
                    $error_message = 'Failed to create account. Please try again.';
                }
            }
        } catch (PDOException $e) {
            $error_message = 'Database error. Please try again later.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - MeatChain Pro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .signup-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px 0;
        }
        .signup-card {
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
        .password-toggle {
            cursor: pointer;
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <div class="signup-card p-5">
                        <div class="text-center mb-4">
                            <i class="bi bi-truck display-4 text-primary mb-3"></i>
                            <h2 class="fw-bold">Join MeatChain Pro</h2>
                            <p class="text-muted">Create your account</p>
                        </div>

                        <?php if ($error_message): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <?php echo htmlspecialchars($error_message); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <?php if ($success_message): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle me-2"></i>
                                <?php echo htmlspecialchars($success_message); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="" id="signupForm">
                            <div class="mb-3">
                                <label for="name" class="form-label fw-semibold">
                                    <i class="bi bi-person me-2"></i>Full Name
                                </label>
                                <input type="text" class="form-control form-control-lg" id="name" name="name" 
                                       placeholder="Enter your full name" required 
                                       value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                            </div>
                            
                            <div class="mb-3">
                                <label for="user_id" class="form-label fw-semibold">
                                    <i class="bi bi-person-circle me-2"></i>User ID
                                </label>
                                <input type="text" class="form-control form-control-lg" id="user_id" name="user_id" 
                                       placeholder="Choose a unique user ID" required 
                                       value="<?php echo isset($_POST['user_id']) ? htmlspecialchars($_POST['user_id']) : ''; ?>">
                            </div>

                            <div class="mb-3 position-relative">
                                <label for="password" class="form-label fw-semibold">
                                    <i class="bi bi-lock me-2"></i>Password
                                </label>
                                <input type="password" class="form-control form-control-lg" id="password" name="password" 
                                       placeholder="Create a strong password" required>
                                <span class="password-toggle" onclick="togglePassword('password', this)">
                                    <i class="bi bi-eye text-muted"></i>
                                </span>
                                <div class="form-text">Must be at least 8 characters with 1 uppercase letter and 1 number</div>
                            </div>

                            <div class="mb-3 position-relative">
                                <label for="confirm_password" class="form-label fw-semibold">
                                    <i class="bi bi-shield-check me-2"></i>Confirm Password
                                </label>
                                <input type="password" class="form-control form-control-lg" id="confirm_password" name="confirm_password" 
                                       placeholder="Confirm your password" required>
                                <span class="password-toggle" onclick="togglePassword('confirm_password', this)">
                                    <i class="bi bi-eye text-muted"></i>
                                </span>
                            </div>

                            <div class="mb-4">
                                <label for="role" class="form-label fw-semibold">
                                    <i class="bi bi-person-badge me-2"></i>Account Type
                                </label>
                                <select class="form-select form-select-lg" id="role" name="role" required>
                                    <option value="">Choose your role</option>
                                    <option value="admin" <?php echo (isset($_POST['role']) && $_POST['role'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
                                    <option value="customer" <?php echo (isset($_POST['role']) && $_POST['role'] === 'customer') ? 'selected' : ''; ?>>Customer</option>
                                </select>
                            </div>

                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary btn-lg fw-semibold">
                                    <i class="bi bi-person-plus me-2"></i>Create Account
                                </button>
                            </div>
                        </form>
                        
                        <div class="text-center">
                            <p class="text-muted mb-2">Already have an account?</p>
                            <a href="login.php" class="btn btn-outline-primary">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword(fieldId, toggleElement) {
            const field = document.getElementById(fieldId);
            const icon = toggleElement.querySelector('i');
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }

        // Client-side password validation
        document.getElementById('signupForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match!');
                return false;
            }
            
            if (password.length < 8) {
                e.preventDefault();
                alert('Password must be at least 8 characters long!');
                return false;
            }
            
            if (!/^(?=.*[A-Z])(?=.*\d)/.test(password)) {
                e.preventDefault();
                alert('Password must contain at least one uppercase letter and one number!');
                return false;
            }
        });
    </script>
</body>
</html>
