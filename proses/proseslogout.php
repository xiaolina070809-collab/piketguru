<?php
require_once __DIR__.'/session.php';
require_once __DIR__.'/helper.php';
if($_SERVER['REQUEST_METHOD']!=='POST') {http_response_code(405);exit('Metode tidak diizinkan.');}
check_csrf();
$_SESSION=[];session_regenerate_id(true);
go('home');
