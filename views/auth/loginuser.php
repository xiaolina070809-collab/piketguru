<div class="row justify-content-center my-4"><div class="col-md-6 col-lg-5">
<div class="card p-3 p-md-4"><div class="card-body">
<div class="feature-icon mx-auto mb-3"><i class="fas fa-lock"></i></div>
<h2 class="text-center font-weight-bold">Masuk ke PiketGuru</h2>
<p class="text-muted text-center mb-4">Gunakan akun yang diberikan administrator sekolah.</p>
<form method="post" action="proses/proseslogin.php"><?=csrf_field()?>
<div class="form-group"><label for="username">Username</label><input id="username" class="form-control" name="username" required autocomplete="username" placeholder="Masukkan username"></div>
<div class="form-group"><label for="password">Password</label><input id="password" class="form-control" type="password" name="password" required autocomplete="current-password" placeholder="Masukkan password"></div>
<button class="btn btn-primary btn-block btn-lg mt-4"><i class="fas fa-sign-in-alt mr-2"></i> Masuk</button>
</form></div></div></div></div>
