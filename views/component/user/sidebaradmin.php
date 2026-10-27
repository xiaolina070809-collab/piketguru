<?php foreach(['dashboard'=>'Dashboard','guru'=>'Data Guru','user'=>'Pengguna','jadwal'=>'Jadwal Piket','absensi'=>'Absensi','tamu'=>'Data Tamu','laporan'=>'Laporan Piket'] as $p=>$label): ?>
<li class="nav-item"><a class="nav-link" href="<?=url($p)?>"><?=e($label)?></a></li>
<?php endforeach; ?>
