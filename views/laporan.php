<?php
// File: views/laporan.php
session_start();
require_once '../config/database.php';

// Cek Login
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php");
    exit;
}

// Ambil Data Filter dari URL (Biar sesuai sama yang dilihat di dashboard)
$bulan_dipilih = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
$tahun_dipilih = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');
$user_id       = $_SESSION['user_id'];
$nama_user     = $_SESSION['nama'];

// Array Nama Bulan
$nama_bulan = [
    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
    '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
    '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
];

// --- HITUNG RINGKASAN ---
// Pemasukan
$q_masuk = mysqli_query($koneksi, "SELECT SUM(nominal) as total FROM transaksi WHERE user_id='$user_id' AND tipe='pemasukan' AND MONTH(tanggal)='$bulan_dipilih' AND YEAR(tanggal)='$tahun_dipilih'");
$total_masuk = mysqli_fetch_assoc($q_masuk)['total'] ?? 0;

// Pengeluaran
$q_keluar = mysqli_query($koneksi, "SELECT SUM(nominal) as total FROM transaksi WHERE user_id='$user_id' AND tipe='pengeluaran' AND MONTH(tanggal)='$bulan_dipilih' AND YEAR(tanggal)='$tahun_dipilih'");
$total_keluar = mysqli_fetch_assoc($q_keluar)['total'] ?? 0;

// Saldo
$saldo = $total_masuk - $total_keluar;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan - <?= $nama_bulan[$bulan_dipilih] . " " . $tahun_dipilih; ?></title>
    <style>
        /* CSS KHUSUS CETAK/LAPORAN */
        body { font-family: 'Times New Roman', serif; padding: 40px; color: #000; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px double #000; padding-bottom: 15px; }
        .header h1 { margin: 0; font-size: 24px; text-transform: uppercase; }
        .header p { margin: 5px 0; font-size: 14px; }
        
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 5px; }

        .data-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .data-table th, .data-table td { border: 1px solid #000; padding: 8px 12px; text-align: left; font-size: 14px; }
        .data-table th { background-color: #f0f0f0; }
        
        .summary-box { margin-top: 20px; display: flex; gap: 20px; justify-content: flex-end; }
        .summary-item { border: 1px solid #000; padding: 10px 20px; min-width: 150px; }
        
        .signature { margin-top: 50px; text-align: right; margin-right: 50px; }
        .signature p { margin-bottom: 70px; }

        /* Tombol Print (Akan hilang pas di-print) */
        .no-print { position: fixed; bottom: 20px; right: 20px; background: #2c3e50; color: white; padding: 15px 25px; border-radius: 50px; text-decoration: none; font-family: sans-serif; box-shadow: 0 5px 15px rgba(0,0,0,0.2); cursor: pointer; border: none; font-size: 16px; }
        .no-print:hover { background: #34495e; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>

    <button onclick="window.print()" class="no-print">🖨️ Cetak / Simpan PDF</button>

    <div class="header">
        <h1>Laporan Keuangan Pribadi</h1>
        <p>Aplikasi UangKemana - Manajemen Finansial Cerdas</p>
    </div>

    <table class="info-table">
        <tr>
            <td width="150"><strong>Nama Pengguna</strong></td>
            <td>: <?= $nama_user; ?></td>
            <td width="150" style="text-align: right;"><strong>Periode</strong></td>
            <td width="150" style="text-align: right;">: <?= $nama_bulan[$bulan_dipilih] . " " . $tahun_dipilih; ?></td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th width="30">No</th>
                <th width="100">Tanggal</th>
                <th>Keterangan</th>
                <th>Kategori</th>
                <th>Tipe</th>
                <th style="text-align: right;">Nominal (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $query = "SELECT * FROM transaksi WHERE user_id = '$user_id' AND MONTH(tanggal) = '$bulan_dipilih' AND YEAR(tanggal) = '$tahun_dipilih' ORDER BY tanggal ASC";
            $tampil = mysqli_query($koneksi, $query);

            if (mysqli_num_rows($tampil) > 0) {
                while ($data = mysqli_fetch_array($tampil)) {
                    $tipe_label = ($data['tipe'] == 'pemasukan') ? 'Masuk' : 'Keluar';
                    $style_nominal = ($data['tipe'] == 'pemasukan') ? 'font-weight:bold;' : '';
            ?>
                <tr>
                    <td style="text-align: center;"><?= $no++; ?></td>
                    <td><?= date('d/m/Y', strtotime($data['tanggal'])); ?></td>
                    <td><?= $data['keterangan']; ?></td>
                    <td><?= $data['kategori']; ?></td>
                    <td><?= $tipe_label; ?></td>
                    <td style="text-align: right; <?= $style_nominal; ?>">
                        <?= number_format($data['nominal'], 0, ',', '.'); ?>
                    </td>
                </tr>
            <?php 
                }
            } else {
                echo "<tr><td colspan='6' style='text-align:center; padding: 20px;'>Tidak ada data transaksi.</td></tr>";
            } 
            ?>
        </tbody>
        <tfoot>
            <tr style="background-color: #f9f9f9; font-weight: bold;">
                <td colspan="5" style="text-align: right;">Total Pemasukan</td>
                <td style="text-align: right; color: green;">Rp <?= number_format($total_masuk, 0, ',', '.'); ?></td>
            </tr>
            <tr style="background-color: #f9f9f9; font-weight: bold;">
                <td colspan="5" style="text-align: right;">Total Pengeluaran</td>
                <td style="text-align: right; color: red;">Rp <?= number_format($total_keluar, 0, ',', '.'); ?></td>
            </tr>
            <tr style="background-color: #eee; font-weight: bold; font-size: 16px;">
                <td colspan="5" style="text-align: right;">SISA SALDO</td>
                <td style="text-align: right; color: blue;">Rp <?= number_format($saldo, 0, ',', '.'); ?></td>
            </tr>
        </tfoot>
    </table>

    <div class="signature">
        <p>Jakarta, <?= date('d F Y'); ?><br>Pemilik Akun,</p>
        <br><br>
        <strong>( <?= $nama_user; ?> )</strong>
    </div>

    <script>
        // Opsional: Otomatis muncul print dialog pas dibuka
        // window.print();
    </script>
</body>
</html>