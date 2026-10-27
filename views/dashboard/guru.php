<?php require_login(); ?>
<div class="hero mb-4"><div class="position-relative" style="z-index:1">
<span class="badge badge-light mb-3 px-3 py-2">DASHBOARD GURU</span>
<h1>Selamat datang,<br><?=e($_SESSION['user']['nama'])?>!</h1>
<p class="mt-3">Lihat jadwal piketmu, isi absensi, catat tamu, dan buat laporan kegiatan hari ini.</p>
</div></div>
<div class="row"><?php foreach([
 ['jadwalsaya','fa-calendar-alt','Jadwal Saya','Lihat jadwal tugas piket'],
 ['absensi','fa-user-check','Absensi','Isi dan lihat kehadiran'],
 ['tamu','fa-address-book','Buku Tamu','Catat kunjungan sekolah'],
 ['laporan','fa-file-alt','Laporan','Catat kegiatan piket']
] as $item): ?>
<div class="col-md-6 col-lg-3 mb-3"><a class="text-reset text-decoration-none" href="<?=url($item[0])?>"><div class="card feature-card"><div class="feature-icon"><i class="fas <?=e($item[1])?>"></i></div><h5><?=e($item[2])?></h5><p class="text-muted mb-0"><?=e($item[3])?></p></div></a></div>
<?php endforeach; ?></div>
