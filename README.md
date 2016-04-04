# BlueTape

BlueTape adalah aplikasi+framework untuk membuat urusan-urusan paper-based di FTIS UNPAR menjadi paperless. Aplikasi ini berbasis web dengan memanfaatkan CodeIgniter + ZURB Foundation.

Fitur-fitur:

* Framework disediakan untuk menambah layanan baru. Menu sudah disediakan, developer tinggal menambah dalam bentuk modul (lihat `CONTRIBUTING.md`)
* Layanan OAuth ke Google, memungkinkan autentikasi pengguna dan menentukan hak akses yang bisa dilihat dari alamat email pengguna, misalnya: membatasi akses ke mahasiswa Informatika ke email `73.....@student.unpar.ac.id`, akses ke mahasiswa FTIS ke `7[0123].....@student.unpar.ac.id`. Untuk staf TU / dosen bisa juga dengan mendaftarkan email staf / dosen.

Saat ini baru tersedia layanan:

* *Transkrip Request / Manage* untuk melakukan permohonan serta pencetakan transkrip mahasiswa.
