# 💸 UangKemana - Personal Finance Manager

Aplikasi manajemen keuangan pribadi berbasis web yang membantu pengguna mencatat pemasukan, pengeluaran, serta memantau arus kas (cashflow) dengan visualisasi grafik yang interaktif.

🔴 **LIVE DEMO:** [Klik Disini buat Coba Aplikasinya](http://uangkemana.page.gd)

## 🚀 Fitur Unggulan

* **Google Login (OAuth):** Masuk dengan cepat dan aman menggunakan akun Google.
* **Cashflow Dashboard:** Ringkasan Pemasukan, Pengeluaran, dan Sisa Saldo secara Real-time.
* **Interactive Charts:** Visualisasi pengeluaran menggunakan Chart.js.
* **Laporan PDF:** Fitur cetak laporan keuangan bulanan otomatis siap print.
* **Manajemen Kategori:** Kategori dinamis yang berubah sesuai tipe transaksi (Masuk/Keluar).
* **User Experience:** Notifikasi interaktif (SweetAlert2), Datepicker modern (Flatpickr), dan Dropdown search (Tom Select).

## 🛠️ Teknologi yang Digunakan

* **Backend:** PHP Native (No Framework)
* **Frontend:** HTML5, CSS3 (Modern UI), JavaScript
* **Database:** MySQL
* **Libraries:**
    * Google API Client (OAuth 2.0)
    * Chart.js (Visualisasi Data)
    * SweetAlert2 (Alert Cantik)
    * Flatpickr & Tom Select (Form Enhancer)

## 📦 Cara Install (Localhost)

1.  Clone repository ini:
    ```bash
    git clone [https://github.com/username-lu/UangKemana.git](https://github.com/username-lu/UangKemana.git)
    ```
2.  Pindahkan folder ke dalam `htdocs` (jika menggunakan XAMPP).
3.  Import database:
    * Buka phpMyAdmin.
    * Buat database baru bernama `uangkemana`.
    * Import file `uangkemana.sql` yang ada di folder project.
4.  Konfigurasi Google Client ID:
    * Buka `actions/auth_google.php`.
    * Masukkan Client ID dan Secret Key Anda.
5.  Jalankan project di browser: `http://localhost/uangkemana`

---
Dibuat dengan ❤️ oleh **[Naninunennn - Hannan Fathur Hendrawan]**
