<?php
require_admin();
$stats=[
 ['guru','Guru','fa-chalkboard-teacher'],
 ['jadwal_piket','Jadwal Piket','fa-calendar-alt'],
 ['absensi_piket','Catatan Absensi','fa-user-check'],
 ['tamu','Kunjungan Tamu','fa-address-book'],
 ['laporan_piket','Laporan','fa-file-alt'],
 ['user','Akun Pengguna','fa-users']
];
$routes=['guru'=>'guru','jadwal_piket'=>'jadwal','absensi_piket'=>'absensi','tamu'=>'tamu','laporan_piket'=>'laporan','user'=>'user'];
?>
<div class="d-flex justify-content-between align-items-center flex-wrap mb-4"><div><h2 class="font-weight-bold mb-1">Dashboard Admin</h2><p class="text-muted mb-0">Ringkasan aktivitas pengelolaan piket sekolah</p></div><span class="badge badge-primary p-2"><?=date('d-m-Y')?></span></div>
<div class="row"><?php foreach($stats as [$table,$label,$icon]): $count=(int)$pdo->query("SELECT COUNT(*) FROM $table")->fetchColumn(); ?>
<div class="col-md-6 col-lg-4 mb-3"><a href="<?=url($routes[$table])?>" class="text-reset text-decoration-none"><div class="card stat-card h-100">
<div class="d-flex justify-content-between align-items-start"><div><div class="label"><?=e($label)?></div><div class="value"><?=number_format($count,0,',','.')?></div></div><div class="feature-icon"><i class="fas <?=e($icon)?>"></i></div></div>
<small class="text-primary mt-2">Kelola data <i class="fas fa-arrow-right"></i></small></div></a></div>
<?php endforeach; ?></div>
<div class="card"><div class="card-body"><h5 class="font-weight-bold">Mulai kelola piket guru</h5><p class="text-muted">Tambahkan guru, atur jadwal, lalu buat akun guru yang terhubung dengan data guru.</p><a class="btn btn-primary" href="<?=url('guru')?>">Kelola Guru</a> <a class="btn btn-outline-primary" href="<?=url('jadwal')?>">Atur Jadwal</a></div></div>
