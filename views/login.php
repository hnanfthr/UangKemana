<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - UangKemana</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    <div class="split-screen">
        <div class="left-pane">
            <img src="https://img.freepik.com/free-vector/wallet-concept-illustration_114360-1127.jpg?w=740" alt="Wallet Illustration" style="border-radius: 20px;">
            <h2>Kembali Pantau Uangmu 💸</h2>
            <p>Masuk ke dashboard untuk melihat analisis pengeluaran harianmu.</p>
        </div>

        <div class="right-pane">
            <div class="auth-box">
                <h1>Selamat Datang! 👋</h1>
                <p>Silakan masuk ke akunmu.</p>

                <a href="../actions/auth_google.php" class="btn-google">
                    <img src="https://cdn-icons-png.flaticon.com/512/2991/2991148.png" alt="Google Logo">
                    Masuk dengan Google
                </a>

                <div class="separator">ATAU LOGIN BIASA</div>

                <?php if(isset($_GET['pesan']) && $_GET['pesan'] == 'gagal') { ?>
                    <div style="background: #ffecec; color: #e74c3c; padding: 10px; border-radius: 8px; margin-bottom: 20px; font-weight: 600; font-size: 13px; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-times-circle"></i> Username atau Password salah!
                    </div>
                <?php } ?>

                <form action="../actions/auth_login.php" method="POST">
                    <div class="input-group">
                        <input type="text" name="username" placeholder="Username" required autocomplete="off">
                        <i class="fas fa-user"></i>
                    </div>

                    <div class="input-group">
                        <input type="password" name="password" placeholder="Password" required>
                        <i class="fas fa-lock"></i>
                    </div>

                    <button type="submit" class="btn-block">MASUK DASHBOARD</button>
                </form>

                <div style="text-align: center; margin-top: 25px;">
                    <span style="color: #888;">Belum punya akun?</span>
                    <a href="register.php" style="color: var(--primary); font-weight: 700; text-decoration: none;">Daftar Dulu</a>
                </div>
                
                <div style="text-align: center; margin-top: 20px;">
                    <a href="../index.php" style="color: #ccc; font-size: 12px; text-decoration: none;">&larr; Kembali ke Home</a>
                </div>
            </div>
        </div>
    </div>

    <?php if(isset($_GET['pesan']) && $_GET['pesan'] == 'sukses') { ?>
        <script>
            Swal.fire({
                title: 'Berhasil Daftar! 🎉',
                text: 'Akun kamu sudah siap. Silakan login sekarang.',
                icon: 'success',
                confirmButtonColor: '#27ae60',
                confirmButtonText: 'Oke, Siap!'
            });
        </script>
    <?php } ?>

</body>
</html>