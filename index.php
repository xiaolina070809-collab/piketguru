<?php
require_once __DIR__.'/proses/session.php';
require_once __DIR__.'/proses/koneksi.php';
require_once __DIR__.'/proses/helper.php';
$halaman=(string)($_GET['halaman']??'home');
$public=['home','tentang','kontak','loginuser'];
$data=['guru','user','jadwal','absensi','tamu','laporan'];
if(!in_array($halaman,$public,true))require_login();
if(in_array($halaman,['guru','user','jadwal'],true))require_admin();
if(logged() && $halaman==='loginuser')go('dashboard');
$title='APK Piket Guru';
include __DIR__.'/views/component/header.php';
include __DIR__.'/views/component/'.(logged()?'user':'tamu').'/navbar.php';
?>
<div class="content-wrapper" style="min-height:75vh"><div class="content-header"></div><section class="content"><div class="container py-3">
<?php if(!empty($_SESSION['flash'])): $f=$_SESSION['flash'];unset($_SESSION['flash']); ?>
<div class="alert alert-<?=e($f['type'])?> alert-dismissible fade show"><?=e($f['message'])?>
<button type="button" class="close" data-dismiss="alert">&times;</button></div><?php endif; ?>
<?php
if(in_array($halaman,$data,true)) {
    $paths=[
      'guru'=>'views/guru/dataguru.php',
      'user'=>'views/pengguna/datauser.php',
      'jadwal'=>'views/jadwal/datajadwal.php',
      'absensi'=>'views/absensi/dataabsensi.php',
      'tamu'=>'views/tamu/datatamu.php',
      'laporan'=>'views/laporan/laporanpiket.php'
    ];
    include __DIR__.'/'.$paths[$halaman];
}
elseif ($halaman === 'dashboard') {
    include __DIR__ . '/views/dashboard/' .
        (admin() ? 'admin' : 'guru') . '.php';
}

elseif ($halaman === 'jadwalsaya') {
    include __DIR__ . '/views/jadwal/jadwalsaya.php';
}

elseif ($halaman === 'loginuser') {
    include __DIR__ . '/views/auth/loginuser.php';
}

elseif (in_array($halaman, ['home', 'tentang', 'kontak'], true)) {
    include __DIR__ . '/views/landing/' . $halaman . '.php';
}

else {
    http_response_code(404);
    include __DIR__ . '/views/errors/404.php';
}
?>
</div></section></div>
<?php include __DIR__.'/views/component/footer.php'; ?>
