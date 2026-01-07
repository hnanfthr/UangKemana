<?php
session_start();
include '../config/database.php';

// Keamanan: Cek Login
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    exit("Akses Ditolak");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id         = $_POST['id'];
    $keterangan = $_POST['keterangan'];
    $nominal    = $_POST['nominal'];
    $tanggal    = $_POST['tanggal'];
    $kategori   = $_POST['kategori'];
    $user_id    = $_SESSION['user_id'];

    // Query UPDATE
    // "Ubah tabel transaksi, set datanya jadi yang baru, DIMANA id-nya sekian DAN yang punya user ini"
    $query = "UPDATE transaksi SET 
                keterangan = '$keterangan',
                nominal = '$nominal',
                tanggal = '$tanggal',
                kategori = '$kategori'
              WHERE id = '$id' AND user_id = '$user_id'";

    if (mysqli_query($koneksi, $query)) {
        // Sukses update, balik ke dashboard
        header("Location: ../views/dashboard.php");
    } else {
        echo "Gagal update: " . mysqli_error($koneksi);
    }
}
?>