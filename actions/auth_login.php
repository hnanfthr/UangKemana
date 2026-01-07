<?php
// File: actions/auth_login.php
session_start();
include '../config/database.php';

$username = $_POST['username'];
$password = $_POST['password'];

// Cek username di database
$data = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");

// Hitung jumlah data yang ditemukan
$cek = mysqli_num_rows($data);

if ($cek > 0) {
    $row = mysqli_fetch_assoc($data);

    // Cek Password (User Google passwordnya NULL, jadi harus handle itu juga)
    if ($row['password'] != NULL && $password == $row['password']) { // Note: Kalau pake password_verify, sesuaikan disini
        
        $_SESSION['status'] = "login";
        $_SESSION['user_id'] = $row['id'];
        
        // --- PERBAIKAN DI SINI ---
        // Kita panggil kolom 'nama' (bukan nama_lengkap)
        $_SESSION['nama'] = $row['nama']; 
        
        // Kita panggil foto juga (biar avatar ijo-nya bener)
        $_SESSION['picture'] = $row['picture'];

        header("Location: ../views/dashboard.php");
    } else {
        header("Location: ../views/login.php?pesan=gagal");
    }
} else {
    header("Location: ../views/login.php?pesan=gagal");
}
?>