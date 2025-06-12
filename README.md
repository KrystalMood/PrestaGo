# 🏆 PrestaGo - Sistem Informasi Pencatatan Prestasi


<p align="center">
  <img src="public/images/full-text-logo.png" width="400" alt="PrestaGo Logo">
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-10-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel Version">
  <img src="https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP Version">
  <img src="https://img.shields.io/badge/TailwindCSS-4-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/License-MIT-yellow.svg?style=for-the-badge" alt="License">
</p>

## 📋 Deskripsi

**PrestaGo** adalah aplikasi web modern berbasis Laravel yang dirancang untuk mempermudah pengelolaan dan pencatatan prestasi mahasiswa. Sistem ini memungkinkan mahasiswa, dosen, dan administrator untuk berinteraksi dalam ekosistem yang terintegrasi untuk mengelola berbagai pencapaian akademik dan non-akademik.

## ✨ Fitur Utama

- 🔐 **Multi-role Authentication**
  - Admin (Pengelola sistem)
  - Mahasiswa (Pengguna utama)
  - Dosen (Pembimbing/pengajar)

- 🏅 **Pengelolaan Prestasi**
  - Pencatatan prestasi akademik dan non-akademik
  - Upload bukti prestasi (sertifikat, dokumentasi)
  - Validasi dan verifikasi prestasi oleh admin

- 🥇 **Manajemen Kompetisi**
  - Informasi lomba dan kompetisi
  - Pendaftaran dan tracking keikutsertaan
  - Hasil dan pencapaian

- 📊 **Dashboard & Reporting**
  - Statistik dan visualisasi data prestasi
  - Laporan periodik berbasis periode/semester
  - Export data dalam berbagai format

- 🧑‍🏫 **Sistem Rekomendasi**
  - Pemberian rekomendasi oleh dosen
  - Review dan approval terhadap prestasi

## 🚀 Instalasi

### Prasyarat
- PHP 8.1 atau lebih tinggi
- Composer
- Node.js & NPM
- Database MySQL/MariaDB

### Langkah Instalasi

1. **Clone Repository**
   ```bash
   git clone https://github.com/username/sipp.git
   cd sipp
   ```

2. **Instalasi Dependensi**
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   
   Konfigurasikan database dan setting lainnya di file `.env`

4. **Migrasi Database**
   ```bash
   php artisan migrate --seed
   ```

5. **Kompilasi Asset**
   ```bash
   npm run dev
   ```

6. **Jalankan Aplikasi**
   ```bash
   php artisan serve
   ```
   
   Akses aplikasi melalui browser: `http://localhost:8000`

## 👥 User Roles & Credentials

### Default Login

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@example.com | password |
| Dosen | dosen@example.com | password |
| Mahasiswa | mahasiswa@example.com | password |

## 📝 Panduan Penggunaan

### Mahasiswa
- Mendaftarkan prestasi baru
- Mengupload bukti prestasi
- Melihat status verifikasi
- Melihat riwayat prestasi

### Dosen
- Melihat prestasi mahasiswa
- Memberikan rekomendasi
- Validasi prestasi mahasiswa

### Admin
- Mengelola data master (program studi, periode, jenis kompetisi)
- Verifikasi prestasi mahasiswa
- Mengelola pengguna sistem
- Membuat laporan

## 🔧 Tech Stack

- **Backend**: Laravel 10.x
- **Frontend**: Tailwind CSS 4.x
- **Database**: MySQL/MariaDB
- **Authentication**: Laravel Sanctum

## 🤝 Kontribusi

Kontribusi sangat diterima! Silakan ikuti langkah berikut:

1. Fork repository ini
2. Buat branch baru (`git checkout -b feature/amazing-feature`)
3. Commit perubahan Anda (`git commit -m 'Add some amazing feature'`)
4. Push ke branch (`git push origin feature/amazing-feature`)
5. Buat Pull Request

## 📄 Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE).

## 📞 Kontak

Jika Anda memiliki pertanyaan atau masukan, silakan hubungi kami di [prasuatra@gmail.com](mailto:prasuatra@gmail.com)

---

<p align="center">
  Made with ❤️ by our team :3
</p>
