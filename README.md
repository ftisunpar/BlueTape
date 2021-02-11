# BlueTape

## Deskripsi

BlueTape adalah aplikasi+framework untuk membuat urusan-urusan paper-based di FTIS UNPAR menjadi paperless. Aplikasi ini berbasis web dengan memanfaatkan CodeIgniter + ZURB Foundation.

Fitur-fitur:

* Framework disediakan untuk menambah layanan baru. Menu sudah disediakan, developer tinggal menambah dalam bentuk modul (lihat `CONTRIBUTING.md`)
* Layanan OAuth ke Google, memungkinkan autentikasi pengguna dan menentukan hak akses yang bisa dilihat dari alamat email pengguna, misalnya: membatasi akses ke mahasiswa Informatika ke email `73.....@student.unpar.ac.id`, akses ke mahasiswa FTIS ke `7[0123].....@student.unpar.ac.id`. Untuk staf TU / dosen bisa juga dengan mendaftarkan email staf / dosen.

Saat ini tersedia layanan:

* *Transkrip Request / Manage* untuk melakukan permohonan serta pencetakan transkrip mahasiswa.
* *Perubahan Kuliah Request / Manage* untuk permohonan dan pencetakan perubahan jadwal kuliah oleh dosen.

## Development Setup

1. Clone project dari github
2. Set Apache server mengarah ke direktori `www`
3. Di direktori `www/application/config/`:
  - Copy `database-dev.php` ke `database.php` dan ubah isinya sesuai konfigurasi database lokal
  - Copy `auth-dev.php` ke `auth.php` dan ubah isi bagian `google-clientid` dan `google-clientsecret` dengan konfigurasi OAuth yang Anda dapatkan dari Google. Masuk di URL ini <https://console.cloud.google.com/> untuk mendaftar.
4. Eksekusi <http://localhost/migrate> (atau disesuaikan dengan domain Anda)
