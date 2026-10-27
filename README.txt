APLIKASI PIKET GURU - PERBAIKAN DATABASE
=======================================
Acuan: database/db_piketguru.sql dalam ZIP ini.
Tabel: guru, user, jadwal_piket, absensi_piket, tamu, laporan_piket.
PHP 8.0+ dan MySQL/MariaDB, PDO MySQL aktif.

1. Backup database dan folder aplikasi lama terlebih dahulu.
2. Ganti folder aplikasi dengan folder apkpiketguru dari ZIP ini.
3. Start All Laragon. Periksa proses/koneksi.php:
   host 127.0.0.1, database db_piketguru, user root, password kosong.
   Ubah sesuai pengaturan MySQL Anda.
4. Untuk database baru: buka HeidiSQL > File > Load SQL file,
   pilih database/db_piketguru.sql dan jalankan (F9).
   SQL tidak menghapus tabel/data. CREATE TABLE IF NOT EXISTS tidak
   mengubah kolom tabel lama yang berbeda. Bila struktur database Anda
   berbeda, ekspor SQL database tersebut untuk penyesuaian lebih lanjut.
5. Buka http://localhost/apkpiketguru/proses/buatadmin.php
   HANYA jika belum ada admin. Isi nama, username dan password Anda.
   Tidak ada password bawaan. Admin yang sudah ada tidak diubah/reset.
6. Login di http://localhost/apkpiketguru/index.php?halaman=loginuser
   menggunakan akun yang dibuat. Hapus proses/buatadmin.php setelah selesai.
7. Admin menambah Guru, lalu Pengguna role guru yang terhubung ke Guru.
8. Atur jadwal. Guru dapat mengisi absensi, buku tamu dan laporan.

Jika folder di dalam FALDAN, gunakan alamat:
http://localhost/FALDAN/apkpiketguru/
Semua alamat mengikuti lokasi folder aplikasi.
Login menggunakan USERNAME, bukan email (tabel user tidak memiliki email).
Password tersimpan sebagai hash dari password_hash, bukan teks biasa.
Jika akun lama gagal masuk, admin lain dapat mengganti password melalui Pengguna.
Jangan mengisi kolom password langsung dengan teks biasa melalui HeidiSQL.

PERBAIKAN:
- Memperbaiki sintaks index.php dan menambahkan footer/skrip halaman.
- Redirect login/logout/simpan mengikuti lokasi aplikasi, bukan folder proses.
- Pembuatan admin pertama memakai password pilihan sendiri, tanpa menimpa akun.
- Menjaga admin terakhir, validasi tanggal dan urutan jam piket.
- Pesan duplikasi/relasi database lebih jelas; zona waktu Asia/Jakarta.

Batas verifikasi: lingkungan ini tidak menyediakan PHP/MySQL untuk uji runtime.
Kode dan pemetaan tabel diperiksa statis; uji akhir perlu dilakukan di Laragon.
