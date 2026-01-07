<?php
// File: index.php
session_start(); // Mulai sesi biar bisa cek status login
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UangKemana - Atur Keuanganmu</title>
    <link rel="stylesheet" href="assets/css/landing.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <nav class="navbar">
        <a href="#" class="logo">💸 UangKemana.</a>
        <div class="nav-links">
            <?php if (isset($_SESSION['status']) && $_SESSION['status'] == "login") { ?>
                
                <span style="color: #555; font-weight: 500; margin-right: 15px;">
                    Hai, <strong><?= explode(" ", $_SESSION['nama'])[0]; ?></strong>!
                </span>
                <a href="views/dashboard.php" class="btn-cta">Catat Keuangan <i class="fas fa-arrow-right"></i></a>

            <?php } else { ?>
                
                <a href="views/login.php">Masuk</a>
                <a href="views/register.php" class="btn-cta">Daftar Sekarang</a>

            <?php } ?>
        </div>
    </nav>

    <header class="hero">
        <div class="hero-text">
            <h1>Jangan Biarkan Uangmu <br> <span>Hilang Tanpa Jejak.</span></h1>
            <p>
                Catat setiap pengeluaran, pantau grafik bulanan, dan capai tujuan finansialmu. 
                Sistem pencatatan keuangan modern untuk generasi cerdas.
            </p>
            <div style="margin-top: 30px;">
                
                <?php if (isset($_SESSION['status']) && $_SESSION['status'] == "login") { ?>
                    <a href="views/dashboard.php" class="btn-cta" style="padding: 15px 40px; font-size: 1.1rem; background: #2980b9;">
                        Lanjut Catat Keuangan 📝
                    </a>
                <?php } else { ?>
                    <a href="views/register.php" class="btn-cta" style="padding: 15px 40px; font-size: 1.1rem;">
                        Mulai Gratis 🚀
                    </a>
                <?php } ?>

            </div>
        </div>
        
        <div class="hero-image">
            <img src="assets/img/gambar_welcome_page.jpg" alt="Ilustrasi Keuangan">
        </div>
    </header>

    <section class="features">
        <h2>Kenapa Harus UangKemana?</h2>
        <div class="grid-features">
            <div class="feature-card">
                <i class="fas fa-chart-pie"></i>
                <h3>Visualisasi Grafik</h3>
                <p>Lihat kemana uangmu pergi dengan grafik donat yang interaktif dan mudah dipahami.</p>
            </div>
            
            <div class="feature-card">
                <i class="fas fa-shield-alt"></i>
                <h3>Aman & Privat</h3>
                <p>Setiap akun memiliki data sendiri. Privasimu terjaga dengan sistem enkripsi modern.</p>
            </div>

            <div class="feature-card">
                <i class="fas fa-calendar-alt"></i>
                <h3>Laporan Bulanan</h3>
                <p>Filter riwayat pengeluaranmu berdasarkan bulan dan tahun untuk evaluasi yang lebih baik.</p>
            </div>
        </div>
    </section>

    <footer>
        <p>&copy; 2026 UangKemana Project. Catet Pengeluaran lo, biar engga ada bahasa "Lah, kok duit gue tinggal segini?".</p>
    </footer>

</body>
</html>