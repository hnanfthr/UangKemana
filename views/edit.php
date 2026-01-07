<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Ambil data lama
$query = "SELECT * FROM transaksi WHERE id = '$id' AND user_id = '$user_id'";
$result = mysqli_query($koneksi, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data - UangKemana</title>
    
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">

    <style>
        body {
            background-color: #f4f6f8;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .edit-card {
            background: white;
            width: 100%;
            max-width: 450px;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        /* Custom Tom Select biar senada */
        .ts-control {
            border: 2px solid #eee !important;
            border-radius: 12px !important;
            padding: 12px 15px !important;
            box-shadow: none !important;
        }
        .ts-control.focus { border-color: #f39c12 !important; } /* Warna Kuning Edit */
    </style>
</head>
<body>

    <div class="edit-card">
        <div style="text-align: center; margin-bottom: 30px;">
            <div style="background: #fff5e6; color: #f39c12; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 1.5rem;">
                <i class="fas fa-edit"></i>
            </div>
            <h2 style="color: #2c3e50;">Edit Transaksi</h2>
            <p style="color: #7f8c8d;">Ada yang salah input?</p>
        </div>

        <form action="../actions/update.php" method="POST">
            <input type="hidden" name="id" value="<?= $data['id']; ?>">

            <div class="form-group">
                <label>Item / Keterangan</label>
                <input type="text" name="keterangan" class="form-input" value="<?= $data['keterangan']; ?>" required>
            </div>
            
            <div class="form-group">
                <label>Nominal (Rp)</label>
                <input type="number" name="nominal" class="form-input" value="<?= $data['nominal']; ?>" required>
            </div>

            <div class="form-group">
                <div style="display: flex; gap: 15px;">
                    <div style="flex: 1;">
                        <label>Tanggal</label>
                        <input type="date" name="tanggal" id="editTanggal" class="form-input" value="<?= $data['tanggal']; ?>" required>
                    </div>
                    <div style="flex: 1;">
                        <label>Kategori</label>
                        <select name="kategori" id="editKategori" required>
                            <option value="Makan" <?= ($data['kategori'] == 'Makan') ? 'selected' : ''; ?>>Makanan</option>
                            <option value="Transport" <?= ($data['kategori'] == 'Transport') ? 'selected' : ''; ?>>Transport</option>
                            <option value="Hobi" <?= ($data['kategori'] == 'Hobi') ? 'selected' : ''; ?>>Hobi</option>
                            <option value="Tagihan" <?= ($data['kategori'] == 'Tagihan') ? 'selected' : ''; ?>>Tagihan</option>
                            <option value="Lainnya" <?= ($data['kategori'] == 'Lainnya') ? 'selected' : ''; ?>>Lainnya</option>
                        </select>
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 30px;">
                <a href="dashboard.php" style="flex: 1; padding: 15px; text-align: center; border-radius: 12px; background: #f4f6f8; color: #7f8c8d; font-weight: 600;">Batal</a>
                <button type="submit" style="flex: 1; padding: 15px; border-radius: 12px; border: none; background: #f39c12; color: white; font-weight: 600; cursor: pointer; box-shadow: 0 5px 15px rgba(243, 156, 18, 0.3);">
                    UPDATE
                </button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    
    <script>
        // Aktifkan Flatpickr (Tanggal)
        flatpickr("#editTanggal", {
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "d F Y",
            theme: "airbnb"
        });

        // Aktifkan Tom Select (Kategori)
        new TomSelect("#editKategori",{
            create: false,
            sortField: { field: "text", direction: "asc" }
        });
    </script>
</body>
</html>