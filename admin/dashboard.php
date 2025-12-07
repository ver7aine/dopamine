<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
require_once '../includes/database.php';

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Cafe Dopamine</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
        }
        
        .admin-header {
            background-color: #6d4c41;
            color: white;
            padding: 15px 0;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .admin-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .admin-logo {
            font-size: 1.5rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .admin-nav-links {
            display: flex;
            gap: 15px;
        }
        
        .admin-nav-links a {
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        
        .admin-nav-links a:hover, .admin-nav-links a.active {
            background-color: rgba(255,255,255,0.1);
        }
        
        .logout-btn {
            background-color: #e74c3c;
        }
        
        .logout-btn:hover {
            background-color: #c0392b;
        }
        
        .dashboard-container {
            padding: 40px 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .dashboard-title {
            margin-bottom: 30px;
            color: #333;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
            border-left: 4px solid #6d4c41;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .stat-icon {
            font-size: 2.5rem;
            color: #6d4c41;
            margin-bottom: 15px;
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: #333;
            margin: 10px 0;
        }
        
        .stat-label {
            color: #666;
            font-size: 1rem;
        }
        
        .admin-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 30px;
        }
        
        .action-btn {
            display: block;
            background: #6d4c41;
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s, transform 0.3s;
        }
        
        .action-btn:hover {
            background: #5d4037;
            transform: translateY(-3px);
        }
        
        .action-btn i {
            font-size: 2rem;
            margin-bottom: 10px;
            display: block;
        }
        
        .welcome-message {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>
    <header class="admin-header">
        <div class="container">
            <nav class="admin-nav">
                <div class="admin-logo">
                    <i class="fas fa-coffee"></i> Cafe Dopamine Admin
                </div>
                <div class="admin-nav-links">
                    <a href="dashboard.php" class="active">Dashboard</a>
                    <a href="manage_menu.php">Kelola Menu</a>
                    <a href="manage_events.php">Kelola Acara</a>
                    <a href="manage_testimonials.php">Kelola Testimoni</a>
                    <a href="logout.php" class="logout-btn">Logout</a>
                </div>
            </nav>
        </div>
    </header>
    
    <main class="dashboard-container">
        <div class="welcome-message">
            <h1>Selamat Datang, <?php echo $_SESSION['admin_username']; ?>!</h1>
            <p>Anda login sebagai Administrator Cafe Dopamine.</p>
        </div>
        
        <h2 class="dashboard-title">Dashboard Overview</h2>
        
        <div class="stats-grid">
            <?php
            // Get counts from database
            $menu_count = $conn->query("SELECT COUNT(*) as count FROM menu_items")->fetch_assoc()['count'];
            $events_count = $conn->query("SELECT COUNT(*) as count FROM events")->fetch_assoc()['count'];
            $testimonials_count = $conn->query("SELECT COUNT(*) as count FROM testimonials")->fetch_assoc()['count'];
            ?>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-utensils"></i>
                </div>
                <div class="stat-number"><?php echo $menu_count; ?></div>
                <div class="stat-label">Item Menu</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="stat-number"><?php echo $events_count; ?></div>
                <div class="stat-label">Acara</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-comments"></i>
                </div>
                <div class="stat-number"><?php echo $testimonials_count; ?></div>
                <div class="stat-label">Testimoni</div>
            </div>
        </div>
        
        <h2 class="dashboard-title" style="margin-top: 40px;">Quick Actions</h2>
        <div class="admin-actions">
            <a href="manage_menu.php" class="action-btn">
                <i class="fas fa-utensils"></i>
                Kelola Menu
            </a>
            <a href="manage_events.php" class="action-btn">
                <i class="fas fa-calendar-alt"></i>
                Kelola Acara
            </a>
            <a href="manage_testimonials.php" class="action-btn">
                <i class="fas fa-comments"></i>
                Kelola Testimoni
            </a>
            <a href="../index.php" class="action-btn" target="_blank">
                <i class="fas fa-external-link-alt"></i>
                Lihat Website
            </a>
        </div>
    </main>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</body>
</html>
<?php
// Close connection if exists
if (isset($conn)) {
    $conn->close();
}
?>