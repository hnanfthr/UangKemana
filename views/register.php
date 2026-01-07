<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - UangKemana</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <div class="split-screen">
        <div class="left-pane" style="background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);">
            <img src="https://img.freepik.com/free-vector/rocket-concept-illustration_114360-1282.jpg?w=740" alt="Rocket Illustration" style="border-radius: 20px;">
            <h2>Mulai Langkah Awalmu 🚀</h2>
            <p>Bergabunglah sekarang untuk masa depan finansial yang lebih cerah dan terencana.</p>
        </div>

        <div class="right-pane">
            <div class="auth-box">
                <h1>Buat Akun Baru</h1>
                <p>Gratis dan prosesnya cepat.</p>

                <a href="../actions/auth_google.php" class="btn-google">
                    <img src="https://cdn-icons-png.flaticon.com/512/2991/2991148.png" alt="Google Logo">
                    Daftar dengan Google
                </a>

                <div class="separator">ATAU ISI MANUAL</div>

                <form action="../actions/auth_register.php" method="POST">
                    <div class="input-group">
                        <input type="text" name="nama" placeholder="Nama Lengkap" required autocomplete="off">
                        <i class="fas fa-id-card"></i>
                    </div>

                    <div class="input-group">
                        <input type="text" name="username" placeholder="Username" required autocomplete="off">
                        <i class="fas fa-at"></i>
                    </div>

                    <div class="input-group">
                        <input type="password" name="password" placeholder="Password" required>
                        <i class="fas fa-lock"></i>
                    </div>

                    <button type="submit" class="btn-block" style="background: #3498db;">DAFTAR SEKARANG</button>
                </form>

                <div style="text-align: center; margin-top: 25px;">
                    <span style="color: #888;">Sudah punya akun?</span>
                    <a href="login.php" style="color: #3498db; font-weight: 700; text-decoration: none;">Login Disini</a>
                </div>
            </div>
        </div>
    </div>

</body>
</html>