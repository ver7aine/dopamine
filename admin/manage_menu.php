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

// Handle form submission for adding/editing menu
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $name = $conn->real_escape_string($_POST['name'] ?? '');
        $description = $conn->real_escape_string($_POST['description'] ?? '');
        $price = floatval($_POST['price'] ?? 0);
        $category = $conn->real_escape_string($_POST['category'] ?? 'coffee');
        $is_popular = isset($_POST['is_popular']) ? 1 : 0;
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        if ($_POST['action'] === 'add') {
            // Add new menu item
            $sql = "INSERT INTO menu_items (name, description, price, category, is_popular, is_active) 
                    VALUES ('$name', '$description', $price, '$category', $is_popular, $is_active)";
            
            if ($conn->query($sql)) {
                $message = 'Menu item berhasil ditambahkan!';
                $message_type = 'success';
            } else {
                $message = 'Gagal menambahkan menu item: ' . $conn->error;
                $message_type = 'error';
            }
        } elseif ($_POST['action'] === 'edit' && isset($_POST['id'])) {
            // Update menu item
            $id = intval($_POST['id']);
            $sql = "UPDATE menu_items SET 
                    name = '$name', 
                    description = '$description', 
                    price = $price, 
                    category = '$category', 
                    is_popular = $is_popular, 
                    is_active = $is_active,
                    updated_at = NOW()
                    WHERE id = $id";
            
            if ($conn->query($sql)) {
                $message = 'Menu item berhasil diperbarui!';
                $message_type = 'success';
            } else {
                $message = 'Gagal memperbarui menu item: ' . $conn->error;
                $message_type = 'error';
            }
        }
    }
}

// Handle delete request
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $sql = "DELETE FROM menu_items WHERE id = $id";
    
    if ($conn->query($sql)) {
        $message = 'Menu item berhasil dihapus!';
        $message_type = 'success';
    } else {
        $message = 'Gagal menghapus menu item: ' . $conn->error;
        $message_type = 'error';
    }
}

// Fetch all menu items
$menu_items = $conn->query("SELECT * FROM menu_items ORDER BY category, name");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Menu - Cafe Dopamine Admin</title>
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
        
        .category-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .category-coffee {
            background-color: rgba(109, 76, 65, 0.1);
            color: var(--primary-color);
        }
        
        .category-non-coffee {
            background-color: rgba(161, 136, 127, 0.1);
            color: var(--secondary-color);
        }
        
        .category-food {
            background-color: rgba(215, 204, 200, 0.3);
            color: var(--text-color);
        }
        
        .category-dessert {
            background-color: rgba(245, 245, 245, 0.5);
            color: var(--text-light);
        }
        
        .status-active {
            color: #28a745;
            font-weight: 500;
        }
        
        .status-inactive {
            color: #dc3545;
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
        
        .price-input {
            max-width: 200px;
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
                    <a href="manage_menu.php" class="active">Kelola Menu</a>
                    <a href="manage_events.php">Kelola Acara</a>
                    <a href="manage_testimonials.php">Kelola Testimoni</a>
                    <a href="logout.php" class="logout-btn">Logout</a>
                </div>
            </nav>
        </div>
    </header>
    
    <main class="admin-container">
        <div class="admin-title">
            <h1>Kelola Menu</h1>
            <a href="manage_menu.php?action=add" class="btn-add">
                <i class="fas fa-plus"></i> Tambah Menu Baru
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
                <h2>Tambah Menu Baru</h2>
                <form method="POST" action="">
                    <input type="hidden" name="action" value="add">
                    
                    <div class="form-group">
                        <label for="name">Nama Menu *</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Deskripsi</label>
                        <textarea id="description" name="description" class="form-control" rows="3"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="price">Harga *</label>
                        <input type="number" id="price" name="price" class="form-control price-input" min="0" step="100" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="category">Kategori *</label>
                        <select id="category" name="category" class="form-control" required>
                            <option value="coffee">Kopi</option>
                            <option value="non-coffee">Non-Kopi</option>
                            <option value="food">Makanan</option>
                            <option value="dessert">Dessert</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <div class="checkbox-group">
                            <input type="checkbox" id="is_popular" name="is_popular" value="1">
                            <label for="is_popular">Menu Populer</label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="checkbox-group">
                            <input type="checkbox" id="is_active" name="is_active" value="1" checked>
                            <label for="is_active">Aktif</label>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-save"></i> Simpan Menu
                        </button>
                        <a href="manage_menu.php" class="btn-cancel">Batal</a>
                    </div>
                </form>
            </div>
        
        <?php elseif (isset($_GET['edit'])): 
            // Fetch menu item data for editing
            $id = intval($_GET['edit']);
            $result = $conn->query("SELECT * FROM menu_items WHERE id = $id");
            $menu_item = $result->fetch_assoc();
            
            if ($menu_item):
        ?>
            <!-- Edit Form -->
            <div class="form-container">
                <h2>Edit Menu</h2>
                <form method="POST" action="">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" value="<?php echo $menu_item['id']; ?>">
                    
                    <div class="form-group">
                        <label for="name">Nama Menu *</label>
                        <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($menu_item['name']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Deskripsi</label>
                        <textarea id="description" name="description" class="form-control" rows="3"><?php echo htmlspecialchars($menu_item['description']); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="price">Harga *</label>
                        <input type="number" id="price" name="price" class="form-control price-input" value="<?php echo $menu_item['price']; ?>" min="0" step="100" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="category">Kategori *</label>
                        <select id="category" name="category" class="form-control" required>
                            <option value="coffee" <?php echo $menu_item['category'] === 'coffee' ? 'selected' : ''; ?>>Kopi</option>
                            <option value="non-coffee" <?php echo $menu_item['category'] === 'non-coffee' ? 'selected' : ''; ?>>Non-Kopi</option>
                            <option value="food" <?php echo $menu_item['category'] === 'food' ? 'selected' : ''; ?>>Makanan</option>
                            <option value="dessert" <?php echo $menu_item['category'] === 'dessert' ? 'selected' : ''; ?>>Dessert</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <div class="checkbox-group">
                            <input type="checkbox" id="is_popular" name="is_popular" value="1" <?php echo $menu_item['is_popular'] ? 'checked' : ''; ?>>
                            <label for="is_popular">Menu Populer</label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="checkbox-group">
                            <input type="checkbox" id="is_active" name="is_active" value="1" <?php echo $menu_item['is_active'] ? 'checked' : ''; ?>>
                            <label for="is_active">Aktif</label>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-save"></i> Update Menu
                        </button>
                        <a href="manage_menu.php" class="btn-cancel">Batal</a>
                    </div>
                </form>
            </div>
        
        <?php 
            else: 
                echo '<div class="message error">Menu item tidak ditemukan!</div>';
            endif;
        
        else: 
        ?>
            <!-- Menu Items Table -->
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Nama Menu</th>
                            <th>Deskripsi</th>
                            <th>Harga</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($menu_items->num_rows > 0): ?>
                            <?php while($item = $menu_items->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($item['name']); ?>
                                        <?php if ($item['is_popular']): ?>
                                            <br><small><i class="fas fa-star" style="color: #ffc107;"></i> Populer</small>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars(substr($item['description'], 0, 50)) . (strlen($item['description']) > 50 ? '...' : ''); ?></td>
                                    <td>Rp <?php echo number_format($item['price'], 0, ',', '.'); ?></td>
                                    <td>
                                        <span class="category-badge category-<?php echo $item['category']; ?>">
                                            <?php 
                                            switch($item['category']) {
                                                case 'coffee': echo 'Kopi'; break;
                                                case 'non-coffee': echo 'Non-Kopi'; break;
                                                case 'food': echo 'Makanan'; break;
                                                case 'dessert': echo 'Dessert'; break;
                                            }
                                            ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="<?php echo $item['is_active'] ? 'status-active' : 'status-inactive'; ?>">
                                            <?php echo $item['is_active'] ? 'Aktif' : 'Tidak Aktif'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="manage_menu.php?edit=<?php echo $item['id']; ?>" class="btn-edit">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <a href="manage_menu.php?delete=<?php echo $item['id']; ?>" class="btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus menu ini?');">
                                                <i class="fas fa-trash"></i> Hapus
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 30px;">
                                    <p>Tidak ada data menu.</p>
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