<?php
// File: actions/store.php
session_start();
include '../config/database.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: ../views/login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $tipe       = $_POST['tipe']; // <-- INI BARU
    $keterangan = $_POST['keterangan'];
    $nominal    = $_POST['nominal'];
    $tanggal    = $_POST['tanggal'];
    $kategori   = $_POST['kategori'];
    $user_id    = $_SESSION['user_id'];

    // Query Simpan Data Baru
    $query = "INSERT INTO transaksi (user_id, tipe, keterangan, nominal, tanggal, kategori) 
              VALUES ('$user_id', '$tipe', '$keterangan', '$nominal', '$tanggal', '$kategori')";

    if (mysqli_query($koneksi, $query)) {
        header("Location: ../views/dashboard.php");
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>