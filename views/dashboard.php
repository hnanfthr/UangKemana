<?php
// File: views/dashboard.php
session_start();

// 1. Cek Login
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php");
    exit;
}

require_once '../config/database.php';

$id_user_login = $_SESSION['user_id'];
$nama_user     = $_SESSION['nama'];

// --- LOGIKA FOTO PROFIL ---
$foto_profil = isset($_SESSION['picture']) && !empty($_SESSION['picture']) 
    ? $_SESSION['picture'] 
    : "https://ui-avatars.com/api/?name=".urlencode($nama_user)."&background=27ae60&color=fff&size=128";

// --- FILTER PERIODE ---
$bulan_dipilih = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
$tahun_dipilih = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

$nama_bulan = [
    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
    '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
    '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
];

// --- LOGIKA CASHFLOW ---
// 1. Total Pemasukan
$q_masuk = "SELECT SUM(nominal) as total FROM transaksi 
            WHERE user_id = '$id_user_login' AND tipe = 'pemasukan' 
            AND MONTH(tanggal) = '$bulan_dipilih' AND YEAR(tanggal) = '$tahun_dipilih'";
$d_masuk = mysqli_fetch_assoc(mysqli_query($koneksi, $q_masuk));
$total_masuk = $d_masuk['total'] ?? 0;

// 2. Total Pengeluaran
$q_keluar = "SELECT SUM(nominal) as total FROM transaksi 
             WHERE user_id = '$id_user_login' AND tipe = 'pengeluaran' 
             AND MONTH(tanggal) = '$bulan_dipilih' AND YEAR(tanggal) = '$tahun_dipilih'";
$d_keluar = mysqli_fetch_assoc(mysqli_query($koneksi, $q_keluar));
$total_keluar = $d_keluar['total'] ?? 0;

// 3. Saldo Akhir
$saldo = $total_masuk - $total_keluar;

// --- CHART DATA (Hanya Pengeluaran) ---
$query_chart = "SELECT kategori, SUM(nominal) as total FROM transaksi 
                WHERE user_id = '$id_user_login' AND tipe = 'pengeluaran' 
                AND MONTH(tanggal) = '$bulan_dipilih' AND YEAR(tanggal) = '$tahun_dipilih' 
                GROUP BY kategori";
$result_chart = mysqli_query($koneksi, $query_chart);
$kategori_chart = []; $nominal_chart = [];
while ($row = mysqli_fetch_assoc($result_chart)) { 
    $kategori_chart[] = $row['kategori']; 
    $nominal_chart[] = $row['total']; 
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - UangKemana</title>
    
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .ts-control { border: 2px solid #eee !important; border-radius: 12px !important; padding: 12px 15px !important; box-shadow: none !important; }
        .ts-control.focus { border-color: #27ae60 !important; }
        .ts-dropdown { border-radius: 12px !important; padding: 5px !important; }
        .type-selector { display: flex; gap: 10px; margin-bottom: 20px; }
        .type-selector input[type="radio"] { display: none; }
        .type-selector label { flex: 1; padding: 12px; text-align: center; border: 2px solid #eee; border-radius: 10px; cursor: pointer; font-weight: 600; transition: 0.3s; color: #7f8c8d; }
        #tipeMasuk:checked + label { background: #e8f6ef; border-color: #27ae60; color: #27ae60; }
        #tipeKeluar:checked + label { background: #ffecec; border-color: #e74c3c; color: #e74c3c; }
        .text-masuk { color: #27ae60; font-weight: 700; }
        .text-keluar { color: #e74c3c; font-weight: 700; }
        .profile-pic { width: 50px; height: 50px; border-radius: 50%; object-fit: cover; border: 2px solid #eee; }
    </style>
</head>
<body>

    <div class="container">
        <div class="dashboard-header" style="background: white; padding: 20px; border-radius: 16px; box-shadow: 0 5px 20px rgba(0,0,0,0.03); margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 15px;">
                <img src="<?= $foto_profil; ?>" alt="Profil" class="profile-pic">
                <div>
                    <h1 style="font-size: 1.3rem; color: #2c3e50; margin-bottom: 2px;">Halo, <?= explode(" ", $nama_user)[0]; ?>! 👋</h1>
                    <p style="color: #7f8c8d; font-size: 0.85rem;">Laporan: <strong><?= $nama_bulan[$bulan_dipilih] . " " . $tahun_dipilih; ?></strong></p>
                </div>
            </div>
            
            <div style="display: flex; gap: 10px; align-items: center;">
                <button onclick="bukaModal()" class="btn-add-new">
                    <i class="fas fa-plus"></i> <span style="margin-left:5px; display:none; @media(min-width:600px){display:inline;}">Baru</span>
                </button>
                <a href="../index.php" style="background: #e8f4fd; color: #3498db; padding: 10px 15px; border-radius: 10px;" title="Halaman Depan"><i class="fas fa-home"></i></a>
                <a href="#" onclick="konfirmasiLogout(event, '../actions/logout.php')" class="btn-logout" title="Logout" style="padding: 10px 15px;"><i class="fas fa-sign-out-alt"></i></a>
            </div>
        </div>

        <div class="summary-grid" style="grid-template-columns: repeat(3, 1fr);">
            <div class="summary-card">
                <div class="icon-box" style="background: #e8f6ef; color: #27ae60;"><i class="fas fa-arrow-down"></i></div>
                <div><p style="color: #7f8c8d; font-size: 0.8rem;">Pemasukan</p><h2 style="font-size: 1.3rem; color: #27ae60;">Rp <?= number_format($total_masuk, 0, ',', '.'); ?></h2></div>
            </div>
            <div class="summary-card">
                <div class="icon-box" style="background: #ffecec; color: #e74c3c;"><i class="fas fa-arrow-up"></i></div>
                <div><p style="color: #7f8c8d; font-size: 0.8rem;">Pengeluaran</p><h2 style="font-size: 1.3rem; color: #e74c3c;">Rp <?= number_format($total_keluar, 0, ',', '.'); ?></h2></div>
            </div>
            <div class="summary-card" style="background: #2c3e50; color: white;">
                <div class="icon-box" style="background: rgba(255,255,255,0.1); color: white;"><i class="fas fa-wallet"></i></div>
                <div><p style="color: #bdc3c7; font-size: 0.8rem;">Sisa Saldo</p><h2 style="font-size: 1.3rem;">Rp <?= number_format($saldo, 0, ',', '.'); ?></h2></div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 25px;">
            
            <div class="card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h3><i class="fas fa-history" style="color: #f39c12;"></i> Riwayat</h3>
                    
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <form action="" method="GET" style="display: flex; gap: 10px;">
                            <select name="bulan" class="form-input" style="padding: 5px;" onchange="this.form.submit()">
                                <?php foreach ($nama_bulan as $key => $val) { $sel = ($key == $bulan_dipilih) ? 'selected' : ''; echo "<option value='$key' $sel>$val</option>"; } ?>
                            </select>
                            <select name="tahun" class="form-input" style="padding: 5px;" onchange="this.form.submit()">
                                <?php $thn = date('Y'); for ($i = $thn; $i >= $thn - 2; $i--) { $sel = ($i == $tahun_dipilih) ? 'selected' : ''; echo "<option value='$i' $sel>$i</option>"; } ?>
                            </select>
                        </form>

                        <a href="laporan.php?bulan=<?= $bulan_dipilih; ?>&tahun=<?= $tahun_dipilih; ?>" target="_blank" 
                           style="background: #8e44ad; color: white; padding: 7px 12px; border-radius: 8px; text-decoration: none; font-size: 0.9rem;" 
                           title="Cetak Laporan">
                            <i class="fas fa-print"></i>
                        </a>
                    </div>
                    
                </div>
                
                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr><th>Tanggal</th><th>Ket</th><th>Kategori</th><th>Nominal</th><th style="text-align: center;">Aksi</th></tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT * FROM transaksi WHERE user_id = '$id_user_login' AND MONTH(tanggal) = '$bulan_dipilih' AND YEAR(tanggal) = '$tahun_dipilih' ORDER BY tanggal DESC";
                            $tampil = mysqli_query($koneksi, $query);

                            if (mysqli_num_rows($tampil) > 0) {
                                while ($data = mysqli_fetch_array($tampil)) {
                                    if ($data['tipe'] == 'pemasukan') { 
                                        $badge_bg = '#e8f6ef'; $badge_color = '#27ae60'; $nominal_class = 'text-masuk'; $tanda = '+ '; 
                                    } else { 
                                        $badge_bg = '#ffecec'; $badge_color = '#e74c3c'; $nominal_class = 'text-keluar'; $tanda = '- '; 
                                    }
                            ?>
                                <tr>
                                    <td><?= date('d/m', strtotime($data['tanggal'])); ?></td>
                                    <td style="font-weight: 500;"><?= $data['keterangan']; ?></td>
                                    <td><span style="background: <?= $badge_bg; ?>; color: <?= $badge_color; ?>; padding: 4px 8px; border-radius: 5px; font-size: 11px; font-weight: bold;"><?= $data['kategori']; ?></span></td>
                                    <td class="<?= $nominal_class; ?>"><?= $tanda; ?>Rp <?= number_format($data['nominal'], 0, ',', '.'); ?></td>
                                    <td style="text-align: center;">
                                        <a href="edit.php?id=<?= $data['id']; ?>" style="color: #f39c12; margin-right: 10px;"><i class="fas fa-edit"></i></a>
                                        <a href="#" onclick="konfirmasiHapus(event, '../actions/delete.php?id=<?= $data['id']; ?>')" style="color: #ccc;"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php }} else { echo "<tr><td colspan='5' style='text-align:center; padding: 30px; color: #aaa;'>Belum ada data transaksi.</td></tr>"; } ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <h3><i class="fas fa-chart-pie" style="color: #27ae60;"></i> Porsi Pengeluaran</h3>
                <div style="position: relative; height: 300px; width: 100%;">
                    <canvas id="myChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-overlay" id="modalTambah">
        <div class="modal-content">
            <button class="close-modal" onclick="tutupModal()">&times;</button>
            <div style="text-align: center; margin-bottom: 20px;">
                <h2 style="font-size: 1.5rem; color: #2c3e50;">Tambah Transaksi</h2>
                <p style="color: #7f8c8d;">Catat arus keuanganmu</p>
            </div>

            <form action="../actions/store.php" method="POST">
                <div class="type-selector">
                    <input type="radio" name="tipe" id="tipeKeluar" value="pengeluaran" checked onchange="gantiKategori('pengeluaran')">
                    <label for="tipeKeluar"><i class="fas fa-arrow-up"></i> Pengeluaran</label>
                    <input type="radio" name="tipe" id="tipeMasuk" value="pemasukan" onchange="gantiKategori('pemasukan')">
                    <label for="tipeMasuk"><i class="fas fa-arrow-down"></i> Pemasukan</label>
                </div>

                <div class="form-group"><label>Keterangan</label><input type="text" name="keterangan" class="form-input" placeholder="Contoh: Gaji / Nasi Padang" required></div>
                <div class="form-group"><label>Nominal (Rp)</label><input type="number" name="nominal" class="form-input" placeholder="0" required></div>
                <div class="form-group">
                    <div style="display: flex; gap: 15px;">
                        <div style="flex: 1;"><label>Tanggal</label><input type="date" name="tanggal" id="pilihTanggal" class="form-input" required></div>
                        <div style="flex: 1;"><label>Kategori</label><select name="kategori" id="pilihKategori" required></select></div>
                    </div>
                </div>
                <button type="submit" class="btn-primary" style="width: 100%; padding: 15px;">SIMPAN DATA</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

    <script>
        const katPengeluaran = [{v:'Makan', t:'🍽️ Makanan'}, {v:'Transport', t:'🛵 Transport'}, {v:'Hobi', t:'🎮 Hobi'}, {v:'Tagihan', t:'⚡ Tagihan'}, {v:'Lainnya', t:'📦 Lainnya'}];
        const katPemasukan = [{v:'Gaji', t:'💰 Gaji Pokok'}, {v:'Bonus', t:'🎁 Bonus/THR'}, {v:'Usaha', t:'📈 Hasil Usaha'}, {v:'Investasi', t:'📊 Investasi'}, {v:'Lainnya', t:'✨ Lainnya'}];
        let tomSelectInstance;

        function gantiKategori(tipe) {
            if(tomSelectInstance) tomSelectInstance.destroy();
            const selectEl = document.getElementById('pilihKategori'); selectEl.innerHTML = "";
            const data = (tipe === 'pemasukan') ? katPemasukan : katPengeluaran;
            data.forEach(item => { let opt = document.createElement('option'); opt.value = item.v; opt.textContent = item.t; selectEl.appendChild(opt); });
            tomSelectInstance = new TomSelect("#pilihKategori",{ create: false });
        }
        gantiKategori('pengeluaran');

        flatpickr("#pilihTanggal", { dateFormat: "Y-m-d", altInput: true, altFormat: "d F Y", defaultDate: "today", theme: "airbnb" });

        const modal = document.getElementById('modalTambah');
        function bukaModal() { modal.classList.add('active'); }
        function tutupModal() { modal.classList.remove('active'); }
        modal.addEventListener('click', function(e) { if (e.target === modal) tutupModal(); });

        const ctx = document.getElementById('myChart');
        const dataKategori = <?php echo json_encode($kategori_chart); ?>;
        const dataNominal = <?php echo json_encode($nominal_chart); ?>;
        if(dataKategori.length === 0) {
            new Chart(ctx, { type: 'doughnut', data: { labels: ["Kosong"], datasets: [{ data: [1], backgroundColor: ['#eee'] }] }, options: { plugins: { legend: { display: false } } } });
        } else {
            new Chart(ctx, { type: 'doughnut', data: { labels: dataKategori, datasets: [{ data: dataNominal, borderWidth: 0, backgroundColor: ['#e74c3c', '#3498db', '#f1c40f', '#9b59b6', '#95a5a6'], hoverOffset: 15 }] }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } } } } });
        }

        function konfirmasiHapus(event, urlLink) {
            event.preventDefault();
            Swal.fire({ title: 'Hapus data ini?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#e74c3c', confirmButtonText: 'Ya, Hapus', cancelButtonText: 'Batal' }).then((result) => { if (result.isConfirmed) window.location.href = urlLink; })
        }

        function konfirmasiLogout(event, urlLink) {
            event.preventDefault();
            Swal.fire({ title: 'Yakin mau Logout?', text: "Sesi kamu akan berakhir.", icon: 'question', showCancelButton: true, confirmButtonColor: '#3498db', cancelButtonColor: '#95a5a6', confirmButtonText: 'Ya, Keluar', cancelButtonText: 'Batal', reverseButtons: true }).then((result) => { if (result.isConfirmed) window.location.href = urlLink; })
        }
    </script>
</body>
</html>