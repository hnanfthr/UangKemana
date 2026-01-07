<?php
// File: actions/logout.php
session_start();

// Hapus semua data sesi
session_destroy();

// Lempar balik ke halaman login
header("Location: ../views/login.php");
exit;
?>