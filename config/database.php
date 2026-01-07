<?php
// File: config/database.php

$hostname = "localhost";
$username = "root";
$password = "";          // Default XAMPP biasanya kosong
$database = "db_uangkemana"; // Sesuai nama DB yang tadi kita buat

$koneksi = mysqli_connect($hostname, $username, $password, $database);

// Cek koneksi, kalau error langsung matiin website dan kasih pesan
if (!$koneksi) {
    die("Gagal Konek ke Database: " . mysqli_connect_error());
}
?>