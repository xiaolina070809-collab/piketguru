<nav class="main-header navbar navbar-expand-lg navbar-light navbar-white">
<div class="container-fluid"><a class="navbar-brand font-weight-bold" href="<?=url('dashboard')?>"><span class="brand-mark"><i class="fas fa-chalkboard-teacher"></i></span> Piket<span class="text-primary">Guru</span></a>
<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navUser" aria-label="Buka navigasi"><span class="navbar-toggler-icon"></span></button>
<div class="collapse navbar-collapse" id="navUser"><ul class="navbar-nav mr-auto">
<?php include __DIR__.'/'.(admin()?'sidebaradmin.php':'sidebarguru.php'); ?>
</ul><div class="ml-auto d-lg-flex align-items-center">
<span class="mr-3 text-muted"><i class="far fa-user-circle mr-1"></i> <?=e($_SESSION['user']['nama'])?> <small>(<?=e($_SESSION['user']['role'])?>)</small></span>
<form action="proses/proseslogout.php" method="post" class="d-inline"><?=csrf_field()?><button class="btn btn-outline-danger btn-sm"><i class="fas fa-sign-out-alt mr-1"></i> Keluar</button></form>
</div></div></div></nav>
