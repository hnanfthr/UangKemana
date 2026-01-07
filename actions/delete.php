<?php
// File: actions/delete.php

include '../config/database.php';

// Ambil ID dari URL (yang dikirim pas klik tombol hapus)
$id = $_GET['id'];

// Perintah Hapus
$query = "DELETE FROM transaksi WHERE id = '$id'";
$hapus = mysqli_query($koneksi, $query);

if ($hapus) {
    // Kalau sukses, balik ke dashboard
    header("Location: ../views/dashboard.php");
} else {
    echo "Gagal menghapus: " . mysqli_error($koneksi);
}
?>