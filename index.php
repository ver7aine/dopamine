<?php
// Start session at the beginning
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
require_once 'includes/database.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafe Dopamine</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Header & Navigation -->
    <header class="header">
        <div class="container">
            <nav class="navbar">
                <a href="index.php" class="logo">
                    <i class="fas fa-coffee"></i>
                    <span>Cafe Dopamine</span>
                </a>
                <ul class="nav-menu">
                    <li><a href="#home" class="nav-link active">Beranda</a></li>
                    <li><a href="#about" class="nav-link">Tentang</a></li>
                    <li><a href="#menu" class="nav-link">Menu</a></li>
                    <li><a href="#events" class="nav-link">Acara</a></li>
                    <li><a href="#testimonials" class="nav-link">Testimoni</a></li>
                    <li><a href="#contact" class="nav-link">Kontak</a></li>
                    <li><a href="admin/login.php" class="nav-link admin-btn"><i class="fas fa-lock"></i> Admin</a></li>
                </ul>
                <div class="hamburger">
                    <span class="bar"></span>
                    <span class="bar"></span>
                    <span class="bar"></span>
                </div>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">Temukan Kedamaian di Setiap Tegukan</h1>
                <p class="hero-subtitle">Cafe Dopamine menghadirkan pengalaman ngopi yang tak terlupakan dengan suasana yang menenangkan dan kopi berkualitas premium.</p>
                <a href="#menu" class="btn-primary">Lihat Menu Kami</a>
                <a href="#contact" class="btn-secondary">Kunjungi Kami</a>
            </div>
            <div class="hero-image">
                <div class="image-frame">
                    <img src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Kopi spesial di Cafe Dopamine">
                </div>
            </div>
        </div>
        <div class="wave-divider">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z"></path>
            </svg>
        </div>
    </section>

    <!-- About Section -->
    <section class="about" id="about">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Tentang Cafe Dopamine</h2>
                <p class="section-subtitle">Tempat di mana setiap kopi bercerita</p>
            </div>
            <div class="about-content">
                <div class="about-image">
                    <img src="https://images.unsplash.com/photo-1554118811-1e0d58224f24?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Interior Cafe Dopamine">
                </div>
                <div class="about-text">
                    <h3>Pengalaman Ngopi yang Berbeda</h3>
                    <p>Cafe Dopamine didirikan pada tahun 2020 dengan misi memberikan pengalaman ngopi yang tidak hanya memuaskan dahaga tetapi juga menenangkan jiwa. Kami percaya bahwa secangkir kopi yang baik dapat meningkatkan mood dan produktivitas.</p>
                    <p>Kami menggunakan biji kopi pilihan dari berbagai daerah di Indonesia yang dipanggang dengan sempurna untuk menjaga cita rasa asli. Setiap hidangan disajikan dengan penuh perhatian oleh barista kami yang berpengalaman.</p>
                    <div class="features">
                        <div class="feature">
                            <i class="fas fa-seedling"></i>
                            <span>Bahan Organik</span>
                        </div>
                        <div class="feature">
                            <i class="fas fa-mug-hot"></i>
                            <span>Kopi Spesial</span>
                        </div>
                        <div class="feature">
                            <i class="fas fa-wifi"></i>
                            <span>WiFi Cepat</span>
                        </div>
                        <div class="feature">
                            <i class="fas fa-parking"></i>
                            <span>Parkir Luas</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Menu Section -->
    <section class="menu" id="menu">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Menu Andalan Kami</h2>
                <p class="section-subtitle">Citarasa yang memanjakan lidah dan menenangkan jiwa</p>
            </div>
            <div class="filter-buttons">
                <button class="filter-btn active" data-filter="all">Semua</button>
                <button class="filter-btn" data-filter="coffee">Kopi</button>
                <button class="filter-btn" data-filter="non-coffee">Non-Kopi</button>
                <button class="filter-btn" data-filter="food">Makanan</button>
                <button class="filter-btn" data-filter="dessert">Dessert</button>
            </div>
            <div class="menu-grid" id="menu-items">
                <?php
                // Load menu items from database
                $sql = "SELECT * FROM menu_items WHERE is_active = 1 ORDER BY category, name";
                $result = $conn->query($sql);
                
                if ($result->num_rows > 0) {
                    while($item = $result->fetch_assoc()) {
                        $category_text = '';
                        switch($item['category']) {
                            case 'coffee': $category_text = 'Kopi'; break;
                            case 'non-coffee': $category_text = 'Non-Kopi'; break;
                            case 'food': $category_text = 'Makanan'; break;
                            case 'dessert': $category_text = 'Dessert'; break;
                        }
                        
                        echo '
                        <div class="menu-item ' . $item['category'] . '">
                            <div class="menu-item-img">
                                <img src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80" alt="' . htmlspecialchars($item['name']) . '">
                            </div>
                            <div class="menu-item-content">
                                <div class="menu-item-header">
                                    <h3>' . htmlspecialchars($item['name']) . '</h3>
                                    <span class="price">Rp ' . number_format($item['price'], 0, ',', '.') . '</span>
                                </div>
                                <p>' . htmlspecialchars($item['description']) . '</p>
                                <div class="menu-item-footer">
                                    <span class="category-tag ' . $item['category'] . '">' . $category_text . '</span>';
                                    
                        if ($item['is_popular']) {
                            echo '<span class="popular-tag"><i class="fas fa-star"></i> Populer</span>';
                        }
                        
                        echo '
                                </div>
                            </div>
                        </div>';
                    }
                } else {
                    echo '<p class="no-data">Menu sedang tidak tersedia.</p>';
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Events Section -->
    <section class="events" id="events">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Acara & Kegiatan</h2>
                <p class="section-subtitle">Bergabunglah dengan komunitas kami</p>
            </div>
            <div class="events-grid" id="events-list">
                <?php
                // Load events from database
                $sql = "SELECT * FROM events WHERE is_upcoming = 1 ORDER BY date, time";
                $result = $conn->query($sql);
                
                if ($result->num_rows > 0) {
                    while($event = $result->fetch_assoc()) {
                        $event_date = new DateTime($event['date']);
                        $event_day = $event_date->format('d');
                        $event_month = $event_date->format('M');
                        
                        echo '
                        <div class="event-card">
                            <div class="event-date">
                                <span class="event-day">' . $event_day . '</span>
                                <span class="event-month">' . $event_month . '</span>
                            </div>
                            <div class="event-content">
                                <h3>' . htmlspecialchars($event['title']) . '</h3>
                                <p>' . htmlspecialchars($event['description']) . '</p>
                                <div class="event-meta">
                                    <span><i class="fas fa-clock"></i> ' . date('H:i', strtotime($event['time'])) . '</span>
                                    <span><i class="fas fa-users"></i> ' . $event['participants'] . ' Peserta</span>
                                </div>
                                <span class="upcoming-tag">Segera</span>
                            </div>
                        </div>';
                    }
                } else {
                    echo '<p class="no-data">Tidak ada acara mendatang.</p>';
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials" id="testimonials">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Testimoni Pelanggan</h2>
                <p class="section-subtitle">Apa kata mereka tentang Cafe Dopamine</p>
            </div>
            <div class="testimonials-slider">
                <div class="testimonials-container" id="testimonials-container">
                    <?php
                    // Load testimonials from database
                    $sql = "SELECT * FROM testimonials WHERE is_approved = 1 ORDER BY created_at DESC LIMIT 5";
                    $result = $conn->query($sql);
                    
                    if ($result->num_rows > 0) {
                        while($testimonial = $result->fetch_assoc()) {
                            $stars = str_repeat('<i class="fas fa-star"></i>', $testimonial['rating']);
                            
                            echo '
                            <div class="testimonial-card">
                                <div class="testimonial-content">
                                    <div class="rating">' . $stars . '</div>
                                    <p>"' . htmlspecialchars($testimonial['comment']) . '"</p>
                                </div>
                                <div class="testimonial-author">
                                    <div class="author-img">
                                        <img src="https://ui-avatars.com/api/?name=' . urlencode($testimonial['name']) . '&background=random&color=fff" alt="' . htmlspecialchars($testimonial['name']) . '">
                                    </div>
                                    <div class="author-info">
                                        <h4>' . htmlspecialchars($testimonial['name']) . '</h4>
                                        <span>' . ($testimonial['role'] ? htmlspecialchars($testimonial['role']) : 'Pelanggan') . '</span>
                                    </div>
                                </div>
                            </div>';
                        }
                    } else {
                        echo '<p class="no-data">Belum ada testimoni.</p>';
                    }
                    ?>
                </div>
                <button class="slider-btn prev-btn"><i class="fas fa-chevron-left"></i></button>
                <button class="slider-btn next-btn"><i class="fas fa-chevron-right"></i></button>
            </div>
        </div>
    </section>

    <!-- Contact & Location -->
    <section class="contact" id="contact">
        <div class="container">
            <div class="contact-content">
                <div class="contact-info">
                    <h2 class="section-title">Kunjungi Cafe Dopamine</h2>
                    <p class="contact-description">Nikmati suasana nyaman dan kopi berkualitas di lokasi strategis kami.</p>
                    
                    <div class="contact-details">
                        <div class="contact-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <div>
                                <h3>Alamat</h3>
                                <p>Jl. Kenangan Indah No. 123, Jakarta Selatan</p>
                            </div>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-clock"></i>
                            <div>
                                <h3>Jam Buka</h3>
                                <p>Senin - Jumat: 07:00 - 22:00</p>
                                <p>Sabtu - Minggu: 08:00 - 23:00</p>
                            </div>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-phone"></i>
                            <div>
                                <h3>Telepon</h3>
                                <p>(021) 1234-5678</p>
                            </div>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <div>
                                <h3>Email</h3>
                                <p>info@cafedopamine.com</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="social-links">
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>
                <div class="contact-map">
                    <div class="map-placeholder">
                        <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Lokasi Cafe Dopamine">
                        <div class="map-overlay">
                            <a href="#" class="btn-map">
                                <i class="fas fa-map-marked-alt"></i>
                                <span>Buka di Google Maps</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">
                    <a href="#" class="logo">
                        <i class="fas fa-coffee"></i>
                        <span>Cafe Dopamine</span>
                    </a>
                    <p>Tempat kopi dan inspirasi sejak 2020</p>
                </div>
                <div class="footer-links">
                    <h3>Menu Cepat</h3>
                    <ul>
                        <li><a href="#home">Beranda</a></li>
                        <li><a href="#menu">Menu</a></li>
                        <li><a href="#events">Acara</a></li>
                        <li><a href="#about">Tentang Kami</a></li>
                    </ul>
                </div>
                <div class="footer-newsletter">
                    <h3>Berlangganan Newsletter</h3>
                    <p>Dapatkan promo dan info acara terbaru dari kami</p>
                    <form id="newsletter-form">
                        <input type="email" placeholder="Email Anda" required>
                        <button type="submit"><i class="fas fa-paper-plane"></i></button>
                    </form>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2023 Cafe Dopamine.
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script src="js/main.js"></script>
    <script>
        // Testimonial slider functionality
        document.addEventListener('DOMContentLoaded', function() {
            const slider = document.querySelector('.testimonials-slider');
            const container = document.querySelector('.testimonials-container');
            const cards = document.querySelectorAll('.testimonial-card');
            const prevBtn = document.querySelector('.prev-btn');
            const nextBtn = document.querySelector('.next-btn');
            
            if (cards.length > 1) {
                let currentIndex = 0;
                
                function updateSlider() {
                    if (container) {
                        container.style.transform = `translateX(-${currentIndex * 100}%)`;
                    }
                }
                
                if (nextBtn) {
                    nextBtn.addEventListener('click', () => {
                        if (currentIndex < cards.length - 1) {
                            currentIndex++;
                            updateSlider();
                        }
                    });
                }
                
                if (prevBtn) {
                    prevBtn.addEventListener('click', () => {
                        if (currentIndex > 0) {
                            currentIndex--;
                            updateSlider();
                        }
                    });
                }
                
                // Auto slide every 5 seconds
                setInterval(() => {
                    if (currentIndex < cards.length - 1) {
                        currentIndex++;
                    } else {
                        currentIndex = 0;
                    }
                    updateSlider();
                }, 5000);
            }
            
            // Menu filtering
            const filterButtons = document.querySelectorAll('.filter-btn');
            const menuItems = document.querySelectorAll('.menu-item');
            
            filterButtons.forEach(button => {
                button.addEventListener('click', () => {
                    // Remove active class from all buttons
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    // Add active class to clicked button
                    button.classList.add('active');
                    
                    const filterValue = button.getAttribute('data-filter');
                    
                    // Filter menu items
                    menuItems.forEach(item => {
                        if(filterValue === 'all' || item.classList.contains(filterValue)) {
                            item.style.display = 'block';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>
<?php
// Close database connection
if (isset($conn)) {
    $conn->close();
}
?>