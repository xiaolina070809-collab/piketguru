<?php
require_once __DIR__.'/session.php';
require_once __DIR__.'/helper.php';
require_once __DIR__.'/koneksi.php';
try {
    $exists=(int)$pdo->query("SELECT COUNT(*) FROM `user` WHERE role='admin'")->fetchColumn();
} catch(PDOException $e) {
    http_response_code(503);
    exit('Tabel belum sesuai. Import database/db_piketguru.sql melalui HeidiSQL terlebih dahulu.');
}
if($exists>0){
    http_response_code(403);
    exit('Admin sudah tersedia. Gunakan akun yang ada. Perubahan password dilakukan melalui menu Pengguna oleh admin.');
}
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    check_csrf();
    $nama=trim((string)($_POST['nama']??''));
    $username=trim((string)($_POST['username']??''));
    $password=(string)($_POST['password']??'');
    if($nama==='' || strlen($nama)>120 || $username==='' || strlen($username)>60 || strlen($password)<8){
        $error='Isi nama dan username; password minimal 8 karakter.';
    } else {
        try {
            $q=$pdo->prepare("INSERT INTO `user` (nama,username,password,role) VALUES (?,?,?,'admin')");
            $q->execute([$nama,$username,password_hash($password,PASSWORD_DEFAULT)]);
            flash('Admin berhasil dibuat. Masuk menggunakan username dan password yang baru Anda isi.');
            go('loginuser');
        } catch(PDOException $e){ $error='Username sudah digunakan atau struktur tabel tidak sesuai.'; }
    }
}
?>
<!doctype html><html lang="id"><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Buat Admin Piket Guru</title>
<link rel="stylesheet" href="../assets/dist/css/adminlte.min.css">
<body class="bg-light"><main class="container py-5" style="max-width:520px"><div class="card card-body">
<h2>Buat Admin Pertama</h2><p>Tentukan akun Anda sendiri. Halaman ini terkunci setelah admin tersedia.</p>
<?php if($error): ?><div class="alert alert-danger"><?=e($error)?></div><?php endif; ?>
<form method="post"><?=csrf_field()?>
<label>Nama</label><input class="form-control mb-3" name="nama" maxlength="120" required>
<label>Username</label><input class="form-control mb-3" name="username" maxlength="60" autocomplete="username" required>
<label>Password (minimal 8 karakter)</label><input class="form-control mb-3" type="password" name="password" minlength="8" autocomplete="new-password" required>
<button class="btn btn-primary">Buat Admin</button></form></div></main></body></html>
