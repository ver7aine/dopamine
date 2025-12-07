<?php
// Start session at the very beginning
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection with correct path
require_once '../includes/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Simple authentication (in production, use password_hash and password_verify)
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;
        header('Location: dashboard.php');
        exit();
    } else {
        $error = 'Username atau password salah!';
    }
}

// If already logged in, redirect to dashboard
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Cafe Dopamine</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f9f5f3 0%, #f0e6e1 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
        }
        
        .login-box {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .login-title {
            text-align: center;
            margin-bottom: 30px;
            color: #6d4c41;
            font-size: 1.8rem;
        }
        
        .error-message {
            background-color: #ffebee;
            color: #c62828;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 0.9rem;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: 500;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #6d4c41;
        }
        
        .btn-login {
            width: 100%;
            padding: 12px;
            background-color: #6d4c41;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 10px;
        }
        
        .btn-login:hover {
            background-color: #5d4037;
        }
        
        .back-home {
            text-align: center;
            margin-top: 20px;
        }
        
        .back-home a {
            color: #6d4c41;
            text-decoration: none;
        }
        
        .back-home a:hover {
            text-decoration: underline;
        }
        
        .demo-info {
            margin-top: 20px;
            font-size: 12px;
            color: #777;
            text-align: center;
            background: #f9f9f9;
            padding: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h2 class="login-title">
                <i class="fas fa-coffee"></i><br>
                Cafe Dopamine Admin
            </h2>
            
            <?php if ($error): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                
                <button type="submit" class="btn-login">Login</button>
            </form>
            
            <div class="back-home">
                <a href="../index.php">← Kembali ke Beranda</a>
            </div>
        </div>
    </div>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</body>
</html>