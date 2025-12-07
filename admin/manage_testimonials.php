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

// Handle form submission for adding/editing testimonials
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $name = $conn->real_escape_string($_POST['name'] ?? '');
        $role = $conn->real_escape_string($_POST['role'] ?? '');
        $comment = $conn->real_escape_string($_POST['comment'] ?? '');
        $rating = intval($_POST['rating'] ?? 5);
        $is_approved = isset($_POST['is_approved']) ? 1 : 0;
        
        if ($_POST['action'] === 'add') {
            // Add new testimonial
            $sql = "INSERT INTO testimonials (name, role, comment, rating, is_approved) 
                    VALUES ('$name', '$role', '$comment', $rating, $is_approved)";
            
            if ($conn->query($sql)) {
                $message = 'Testimoni berhasil ditambahkan!';
                $message_type = 'success';
            } else {
                $message = 'Gagal menambahkan testimoni: ' . $conn->error;
                $message_type = 'error';
            }
        } elseif ($_POST['action'] === 'edit' && isset($_POST['id'])) {
            // Update testimonial
            $id = intval($_POST['id']);
            $sql = "UPDATE testimonials SET 
                    name = '$name', 
                    role = '$role', 
                    comment = '$comment', 
                    rating = $rating, 
                    is_approved = $is_approved
                    WHERE id = $id";
            
            if ($conn->query($sql)) {
                $message = 'Testimoni berhasil diperbarui!';
                $message_type = 'success';
            } else {
                $message = 'Gagal memperbarui testimoni: ' . $conn->error;
                $message_type = 'error';
            }
        }
    }
}

// Handle delete request
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $sql = "DELETE FROM testimonials WHERE id = $id";
    
    if ($conn->query($sql)) {
        $message = 'Testimoni berhasil dihapus!';
        $message_type = 'success';
    } else {
        $message = 'Gagal menghapus testimoni: ' . $conn->error;
        $message_type = 'error';
    }
}

// Handle approve/reject request
if (isset($_GET['toggle_approve'])) {
    $id = intval($_GET['toggle_approve']);
    
    // Get current status
    $result = $conn->query("SELECT is_approved FROM testimonials WHERE id = $id");
    if ($result->num_rows > 0) {
        $testimonial = $result->fetch_assoc();
        $new_status = $testimonial['is_approved'] ? 0 : 1;
        
        $sql = "UPDATE testimonials SET is_approved = $new_status WHERE id = $id";
        if ($conn->query($sql)) {
            $action = $new_status ? 'disetujui' : 'ditangguhkan';
            $message = "Testimoni berhasil $action!";
            $message_type = 'success';
        } else {
            $message = 'Gagal mengubah status testimoni: ' . $conn->error;
            $message_type = 'error';
        }
    }
}

// Fetch all testimonials
$testimonials = $conn->query("SELECT * FROM testimonials ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Testimoni - Cafe Dopamine Admin</title>
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
        
        .status-approved {
            color: #28a745;
            font-weight: 500;
        }
        
        .status-pending {
            color: #ffc107;
            font-weight: 500;
        }
        
        .action-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .btn-edit, .btn-delete, .btn-approve, .btn-reject {
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
        
        .btn-approve {
            background-color: #28a745;
            color: white;
        }
        
        .btn-approve:hover {
            background-color: #218838;
        }
        
        .btn-reject {
            background-color: #6c757d;
            color: white;
        }
        
        .btn-reject:hover {
            background-color: #545b62;
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
        
        .rating-input {
            display: flex;
            gap: 5px;
            margin-top: 5px;
        }
        
        .rating-input input {
            display: none;
        }
        
        .rating-input label {
            font-size: 1.5rem;
            color: #ddd;
            cursor: pointer;
            transition: var(--transition);
        }
        
        .rating-input input:checked ~ label,
        .rating-input input:hover ~ label,
        .rating-input label:hover,
        .rating-input label:hover ~ label {
            color: #ffc107;
        }
        
        .rating-display {
            color: #ffc107;
        }
        
        .comment-preview {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            border-left: 3px solid var(--primary-color);
            font-style: italic;
            margin-top: 5px;
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
                    <a href="manage_events.php">Kelola Acara</a>
                    <a href="manage_testimonials.php" class="active">Kelola Testimoni</a>
                    <a href="logout.php" class="logout-btn">Logout</a>
                </div>
            </nav>
        </div>
    </header>
    
    <main class="admin-container">
        <div class="admin-title">
            <h1>Kelola Testimoni</h1>
            <a href="manage_testimonials.php?action=add" class="btn-add">
                <i class="fas fa-plus"></i> Tambah Testimoni Baru
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
                <h2>Tambah Testimoni Baru</h2>
                <form method="POST" action="">
                    <input type="hidden" name="action" value="add">
                    
                    <div class="form-group">
                        <label for="name">Nama *</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="role">Posisi/Peran</label>
                        <input type="text" id="role" name="role" class="form-control" placeholder="Contoh: Pelanggan, Mahasiswa, dll.">
                    </div>
                    
                    <div class="form-group">
                        <label for="comment">Testimoni *</label>
                        <textarea id="comment" name="comment" class="form-control" rows="4" required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Rating</label>
                        <div class="rating-input">
                            <input type="radio" id="star5" name="rating" value="5" checked>
                            <label for="star5">★</label>
                            
                            <input type="radio" id="star4" name="rating" value="4">
                            <label for="star4">★</label>
                            
                            <input type="radio" id="star3" name="rating" value="3">
                            <label for="star3">★</label>
                            
                            <input type="radio" id="star2" name="rating" value="2">
                            <label for="star2">★</label>
                            
                            <input type="radio" id="star1" name="rating" value="1">
                            <label for="star1">★</label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="checkbox-group">
                            <input type="checkbox" id="is_approved" name="is_approved" value="1" checked>
                            <label for="is_approved">Disetujui untuk ditampilkan</label>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-save"></i> Simpan Testimoni
                        </button>
                        <a href="manage_testimonials.php" class="btn-cancel">Batal</a>
                    </div>
                </form>
            </div>
        
        <?php elseif (isset($_GET['edit'])): 
            // Fetch testimonial data for editing
            $id = intval($_GET['edit']);
            $result = $conn->query("SELECT * FROM testimonials WHERE id = $id");
            $testimonial = $result->fetch_assoc();
            
            if ($testimonial):
        ?>
            <!-- Edit Form -->
            <div class="form-container">
                <h2>Edit Testimoni</h2>
                <form method="POST" action="">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" value="<?php echo $testimonial['id']; ?>">
                    
                    <div class="form-group">
                        <label for="name">Nama *</label>
                        <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($testimonial['name']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="role">Posisi/Peran</label>
                        <input type="text" id="role" name="role" class="form-control" value="<?php echo htmlspecialchars($testimonial['role']); ?>" placeholder="Contoh: Pelanggan, Mahasiswa, dll.">
                    </div>
                    
                    <div class="form-group">
                        <label for="comment">Testimoni *</label>
                        <textarea id="comment" name="comment" class="form-control" rows="4" required><?php echo htmlspecialchars($testimonial['comment']); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Rating</label>
                        <div class="rating-input">
                            <?php for($i = 5; $i >= 1; $i--): ?>
                                <input type="radio" id="star<?php echo $i; ?>" name="rating" value="<?php echo $i; ?>" <?php echo $testimonial['rating'] == $i ? 'checked' : ''; ?>>
                                <label for="star<?php echo $i; ?>">★</label>
                            <?php endfor; ?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="checkbox-group">
                            <input type="checkbox" id="is_approved" name="is_approved" value="1" <?php echo $testimonial['is_approved'] ? 'checked' : ''; ?>>
                            <label for="is_approved">Disetujui untuk ditampilkan</label>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-save"></i> Update Testimoni
                        </button>
                        <a href="manage_testimonials.php" class="btn-cancel">Batal</a>
                    </div>
                </form>
            </div>
        
        <?php 
            else: 
                echo '<div class="message error">Testimoni tidak ditemukan!</div>';
            endif;
        
        else: 
        ?>
            <!-- Testimonials Table -->
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Testimoni</th>
                            <th>Rating</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($testimonials->num_rows > 0): ?>
                            <?php while($testimonial = $testimonials->fetch_assoc()): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo htmlspecialchars($testimonial['name']); ?></strong>
                                        <?php if ($testimonial['role']): ?>
                                            <br><small><?php echo htmlspecialchars($testimonial['role']); ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="comment-preview">
                                            "<?php echo htmlspecialchars(substr($testimonial['comment'], 0, 80)) . (strlen($testimonial['comment']) > 80 ? '...' : ''); ?>"
                                        </div>
                                    </td>
                                    <td>
                                        <div class="rating-display">
                                            <?php 
                                            for($i = 1; $i <= 5; $i++) {
                                                echo $i <= $testimonial['rating'] ? '★' : '☆';
                                            }
                                            ?>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="<?php echo $testimonial['is_approved'] ? 'status-approved' : 'status-pending'; ?>">
                                            <?php echo $testimonial['is_approved'] ? 'Disetujui' : 'Menunggu'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php echo date('d M Y', strtotime($testimonial['created_at'])); ?>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="manage_testimonials.php?edit=<?php echo $testimonial['id']; ?>" class="btn-edit">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            
                                            <?php if ($testimonial['is_approved']): ?>
                                                <a href="manage_testimonials.php?toggle_approve=<?php echo $testimonial['id']; ?>" class="btn-reject">
                                                    <i class="fas fa-times"></i> Tangguhkan
                                                </a>
                                            <?php else: ?>
                                                <a href="manage_testimonials.php?toggle_approve=<?php echo $testimonial['id']; ?>" class="btn-approve">
                                                    <i class="fas fa-check"></i> Setujui
                                                </a>
                                            <?php endif; ?>
                                            
                                            <a href="manage_testimonials.php?delete=<?php echo $testimonial['id']; ?>" class="btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus testimoni ini?');">
                                                <i class="fas fa-trash"></i> Hapus
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 30px;">
                                    <p>Tidak ada data testimoni.</p>
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