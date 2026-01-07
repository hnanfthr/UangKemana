<?php
// File: actions/auth_register.php
include '../config/database.php';

$nama     = $_POST['nama'];
$username = $_POST['username'];
$password = $_POST['password'];

// Query Simpan
$query = "INSERT INTO users (nama, username, password) VALUES ('$nama', '$username', '$password')";

if (mysqli_query($koneksi, $query)) {
    // BERHASIL: Pindah ke login bawa pesan 'sukses'
    header("Location: ../views/login.php?pesan=sukses");
} else {
    // GAGAL: Balik ke register bawa pesan error (opsional)
    echo "Gagal Daftar: " . mysqli_error($koneksi);
}
?>