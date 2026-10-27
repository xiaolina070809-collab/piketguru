<?php
// Handler khusus laporan; validasi, CSRF dan SQL diproses secara terpusat.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit('Metode tidak diizinkan.'); }
$_POST['entity'] = 'laporan';
require __DIR__.'/prosesdata.php';
