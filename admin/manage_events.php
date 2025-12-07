<?php
session_start();
require_once '../includes/database.php';

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

// Initialize variables
$message = '';
$message_type = '';

// Handle form submission for adding/editing events
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $title = $conn->real_escape_string($_POST['title'] ?? '');
        $description = $conn->real_escape_string($_POST['description'] ?? '');
        $date = $conn->real_escape_string($_POST['date'] ?? '');
        $time = $conn->real_escape_string($_POST['time'] ?? '');
        $location = $conn->real_escape_string($_POST['location'] ?? '');
        $participants = intval($_POST['participants'] ?? 0);
        $is_upcoming = isset($_POST['is_upcoming']) ? 1 : 0;
        
        if ($_POST['action'] === 'add') {
            // Add new event
            $sql = "INSERT INTO events (title, description, date, time, location, participants, is_upcoming) 
                    VALUES ('$title', '$description', '$date', '$time', '$location', $participants, $is_upcoming)";
            
            if ($conn->query($sql)) {
                $message = 'Acara berhasil ditambahkan!';
                $message_type = 'success';
            } else {
                $message = 'Gagal menambahkan acara: ' . $conn->error;
                $message_type = 'error';
            }
        } elseif ($_POST['action'] === 'edit' && isset($_POST['id'])) {
            // Update event
            $id = intval($_POST['id']);
            $sql = "UPDATE events SET 
                    title = '$title', 
                    description = '$description', 
                    date = '$date', 
                    time = '$time', 
                    location = '$location', 
                    participants = $participants, 
                    is_upcoming = $is_upcoming
                    WHERE id = $id";
            
            if ($conn->query($sql)) {
                $message = 'Acara berhasil diperbarui!';
                $message_type = 'success';
            } else {
                $message = 'Gagal memperbarui acara: ' . $conn->error;
                $message_type = 'error';
            }
        }
    }
}

// Handle delete request
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $sql = "DELETE FROM events WHERE id = $id";
    
    if ($conn->query($sql)) {
        $message = 'Acara berhasil dihapus!';
        $message_type = 'success';
    } else {
        $message = 'Gagal menghapus acara: ' . $conn->error;
        $message_type = 'error';
    }
}

// Fetch all events
$events = $conn->query("SELECT * FROM events ORDER BY date DESC, time DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Acara - Cafe Dopamine Admin</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .admin-header {
            background-color: var(--dark-color);
            color: white;
            padding: 15px 0;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .admin-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .admin-logo {
            font-size: 1.5rem;
            font-weight: 600;
        }
        
        .admin-nav-links {
            display: flex;
            gap: 20px;
        }
        
        .admin-nav-links a {
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            transition: var(--transition);
        }
        
        .admin-nav-links a:hover, .admin-nav-links a.active {
            background-color: rgba(255,255,255,0.1);
        }
        
        .logout-btn {
            background: #e74c3c;
        }
        
        .logout-btn:hover {
            background: #c0392b;
        }
        
        .admin-container {
            padding: 40px 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .admin-title {
            margin-bottom: 30px;
            color: var(--dark-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .btn-add {
            background-color: var(--primary-color);
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: var(--transition);
        }
        
        .btn-add:hover {
            background-color: var(--dark-color);
            transform: translateY(-2px);
        }
        
        .message {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-weight: 500;
        }
        
        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .table-container {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 40px;
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: var(--dark-color);
        }
        
        tr:hover {
            background-color: #f8f9fa;
        }
        
        .status-upcoming {
            color: #28a745;
            font-weight: 500;
        }
        
        .status-past {
            color: #6c757d;
            font-weight: 500;
        }
        
        .action-buttons {
            display: flex;
            gap: 10px;
        }
        
        .btn-edit, .btn-delete {
            padding: 5px 10px;
            border-radius: 3px;
            text-decoration: none;
            font-size: 0.9rem;
            transition: var(--transition);
        }
        
        .btn-edit {
            background-color: #ffc107;
            color: #212529;
        }
        
        .btn-edit:hover {
            background-color: #e0a800;
        }
        
        .btn-delete {
            background-color: #dc3545;
            color: white;
        }
        
        .btn-delete:hover {
            background-color: #c82333;
        }
        
        .form-container {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        
        .form-control {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            transition: var(--transition);
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
        }
        
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .checkbox-group input {
            width: auto;
        }
        
        .btn-submit {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: var(--transition);
        }
        
        .btn-submit:hover {
            background-color: var(--dark-color);
        }
        
        .btn-cancel {
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            display: inline-block;
            margin-left: 10px;
        }
        
        .btn-cancel:hover {
            background-color: #545b62;
        }
        
        .form-actions {
            margin-top: 30px;
        }
        
        .datetime-group {
            display: flex;
            gap: 20px;
        }
        
        .datetime-group .form-group {
            flex: 1;
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
                    <a href="dashboard.php">Dashboard</a>
                    <a href="manage_menu.php">Kelola Menu</a>
                    <a href="manage_events.php" class="active">Kelola Acara</a>
                    <a href="manage_testimonials.php">Kelola Testimoni</a>
                    <a href="logout.php" class="logout-btn">Logout</a>
                </div>
            </nav>
        </div>
    </header>
    
    <main class="admin-container">
        <div class="admin-title">
            <h1>Kelola Acara</h1>
            <a href="manage_events.php?action=add" class="btn-add">
                <i class="fas fa-plus"></i> Tambah Acara Baru
            </a>
        </div>
        
        <?php if ($message): ?>
            <div class="message <?php echo $message_type; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['action']) && $_GET['action'] === 'add'): ?>
            <!-- Add/Edit Form -->
            <div class="form-container">
                <h2>Tambah Acara Baru</h2>
                <form method="POST" action="">
                    <input type="hidden" name="action" value="add">
                    
                    <div class="form-group">
                        <label for="title">Judul Acara *</label>
                        <input type="text" id="title" name="title" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Deskripsi</label>
                        <textarea id="description" name="description" class="form-control" rows="4"></textarea>
                    </div>
                    
                    <div class="datetime-group">
                        <div class="form-group">
                            <label for="date">Tanggal *</label>
                            <input type="date" id="date" name="date" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="time">Waktu *</label>
                            <input type="time" id="time" name="time" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="location">Lokasi</label>
                        <input type="text" id="location" name="location" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="participants">Jumlah Peserta</label>
                        <input type="number" id="participants" name="participants" class="form-control" min="0" value="0">
                    </div>
                    
                    <div class="form-group">
                        <div class="checkbox-group">
                            <input type="checkbox" id="is_upcoming" name="is_upcoming" value="1" checked>
                            <label for="is_upcoming">Acara Mendatang</label>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-save"></i> Simpan Acara
                        </button>
                        <a href="manage_events.php" class="btn-cancel">Batal</a>
                    </div>
                </form>
            </div>
        
        <?php elseif (isset($_GET['edit'])): 
            // Fetch event data for editing
            $id = intval($_GET['edit']);
            $result = $conn->query("SELECT * FROM events WHERE id = $id");
            $event = $result->fetch_assoc();
            
            if ($event):
        ?>
            <!-- Edit Form -->
            <div class="form-container">
                <h2>Edit Acara</h2>
                <form method="POST" action="">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" value="<?php echo $event['id']; ?>">
                    
                    <div class="form-group">
                        <label for="title">Judul Acara *</label>
                        <input type="text" id="title" name="title" class="form-control" value="<?php echo htmlspecialchars($event['title']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Deskripsi</label>
                        <textarea id="description" name="description" class="form-control" rows="4"><?php echo htmlspecialchars($event['description']); ?></textarea>
                    </div>
                    
                    <div class="datetime-group">
                        <div class="form-group">
                            <label for="date">Tanggal *</label>
                            <input type="date" id="date" name="date" class="form-control" value="<?php echo $event['date']; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="time">Waktu *</label>
                            <input type="time" id="time" name="time" class="form-control" value="<?php echo substr($event['time'], 0, 5); ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="location">Lokasi</label>
                        <input type="text" id="location" name="location" class="form-control" value="<?php echo htmlspecialchars($event['location']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="participants">Jumlah Peserta</label>
                        <input type="number" id="participants" name="participants" class="form-control" min="0" value="<?php echo $event['participants']; ?>">
                    </div>
                    
                    <div class="form-group">
                        <div class="checkbox-group">
                            <input type="checkbox" id="is_upcoming" name="is_upcoming" value="1" <?php echo $event['is_upcoming'] ? 'checked' : ''; ?>>
                            <label for="is_upcoming">Acara Mendatang</label>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-save"></i> Update Acara
                        </button>
                        <a href="manage_events.php" class="btn-cancel">Batal</a>
                    </div>
                </form>
            </div>
        
        <?php 
            else: 
                echo '<div class="message error">Acara tidak ditemukan!</div>';
            endif;
        
        else: 
        ?>
            <!-- Events Table -->
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Tanggal & Waktu</th>
                            <th>Lokasi</th>
                            <th>Peserta</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($events->num_rows > 0): ?>
                            <?php while($event = $events->fetch_assoc()): 
                                $event_date = new DateTime($event['date']);
                                $today = new DateTime();
                                $is_past = $event_date < $today;
                            ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($event['title']); ?>
                                        <br><small><?php echo htmlspecialchars(substr($event['description'], 0, 50)) . (strlen($event['description']) > 50 ? '...' : ''); ?></small>
                                    </td>
                                    <td>
                                        <?php echo date('d M Y', strtotime($event['date'])); ?><br>
                                        <small><?php echo date('H:i', strtotime($event['time'])); ?> WIB</small>
                                    </td>
                                    <td><?php echo htmlspecialchars($event['location']); ?></td>
                                    <td><?php echo $event['participants']; ?> orang</td>
                                    <td>
                                        <span class="<?php echo $event['is_upcoming'] ? 'status-upcoming' : 'status-past'; ?>">
                                            <?php echo $event['is_upcoming'] ? 'Mendatang' : 'Selesai'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="manage_events.php?edit=<?php echo $event['id']; ?>" class="btn-edit">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <a href="manage_events.php?delete=<?php echo $event['id']; ?>" class="btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus acara ini?');">
                                                <i class="fas fa-trash"></i> Hapus
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 30px;">
                                    <p>Tidak ada data acara.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
<?php $conn->close(); ?>