<?php
declare(strict_types=1);
function e($v): string { return htmlspecialchars((string)($v??''),ENT_QUOTES,'UTF-8'); }
function base_url(): string {
    $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '/index.php');
    $dir = dirname($script);
    if (basename($dir) === 'proses') $dir = dirname($dir);
    return rtrim($dir, '/.');
}
function url(string $page='home'): string { return base_url().'/index.php?halaman='.rawurlencode($page); }
function go(string $page): void { header('Location: '.url($page)); exit; }
function csrf(): string {
    if(empty($_SESSION['csrf'])) $_SESSION['csrf']=bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}
function csrf_field(): string { return '<input type="hidden" name="csrf" value="'.e(csrf()).'">'; }
function check_csrf(): void {
    if(!hash_equals(csrf(),(string)($_POST['csrf']??''))) { http_response_code(403); exit('Token formulir tidak valid.'); }
}
function flash(string $message,string $type='success'): void { $_SESSION['flash']=['message'=>$message,'type'=>$type]; }
function logged(): bool { return isset($_SESSION['user']); }
function admin(): bool { return logged() && $_SESSION['user']['role']==='admin'; }
function require_login(): void { if(!logged()) go('loginuser'); }
function require_admin(): void { require_login(); if(!admin()){http_response_code(403);exit('Akses hanya untuk admin.');} }
function my_guru_id(): int { return (int)($_SESSION['user']['guru_id']??0); }
function day_name(): string {
    $days=['Sunday'=>'Minggu','Monday'=>'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu',
           'Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu'];
    return $days[date('l')];
}
