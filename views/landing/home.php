<section class="hero mb-4">
<div class="position-relative" style="z-index:1">
<span class="badge badge-light mb-3 px-3 py-2">SISTEM INFORMASI SEKOLAH</span>
<h1>Piket guru lebih mudah,<br>sekolah lebih tertata.</h1>
<p class="mt-3">Kelola jadwal piket, catat kehadiran, kunjungan tamu, dan laporan harian dalam satu aplikasi yang praktis.</p>
<a href="<?=url('loginuser')?>" class="btn btn-light btn-lg mt-2"><i class="fas fa-arrow-right mr-2"></i> Mulai Menggunakan</a>
</div></section>
<div class="row">
<?php $fitur=[
 ['fa-calendar-check','Jadwal Piket','Pembagian tugas guru berdasarkan hari dan jam piket.'],
 ['fa-user-check','Absensi Guru','Catat kehadiran guru piket dengan mudah.'],
 ['fa-address-book','Buku Tamu','Simpan kunjungan tamu sekolah secara tertib.'],
 ['fa-file-alt','Laporan Kegiatan','Dokumentasikan kegiatan dan kejadian harian.'],
]; foreach($fitur as $f): ?>
<div class="col-md-6 col-lg-3 mb-3"><div class="card feature-card"><div class="feature-icon"><i class="fas <?=e($f[0])?>"></i></div>
<h5><?=e($f[1])?></h5><p class="text-muted mb-0"><?=e($f[2])?></p></div></div>
<?php endforeach; ?></div>
