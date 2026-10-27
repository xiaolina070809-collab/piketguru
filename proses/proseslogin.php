<?php
require_once __DIR__.'/session.php';
require_once __DIR__.'/koneksi.php';
require_once __DIR__.'/helper.php';
if($_SERVER['REQUEST_METHOD']!=='POST') go('loginuser');
check_csrf();
$username=trim((string)($_POST['username']??''));
$password=(string)($_POST['password']??'');
$q=$pdo->prepare('SELECT id,guru_id,nama,username,password,role FROM user WHERE username=?');
$q->execute([$username]); $u=$q->fetch();
if(!$u || !password_verify($password,$u['password'])){
    flash('Username atau password salah.','danger');go('loginuser');
}
session_regenerate_id(true);
unset($u['password']);$_SESSION['user']=$u;
go('dashboard');
