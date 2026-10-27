<?php
// Handler khusus tamu; validasi, CSRF dan SQL diproses secara terpusat.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit('Metode tidak diizinkan.'); }
$_POST['entity'] = 'tamu';
require __DIR__.'/prosesdata.php';
