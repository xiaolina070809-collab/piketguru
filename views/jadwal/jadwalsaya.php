<h2>Jadwal Piket Saya</h2>
<?php if(!my_guru_id()): ?><div class="alert alert-warning">Akun belum terhubung dengan data guru. Hubungi admin.</div>
<?php else:
$q=$pdo->prepare("SELECT * FROM jadwal_piket WHERE guru_id=? ORDER BY FIELD(hari,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'),jam_mulai");
$q->execute([my_guru_id()]);$rows=$q->fetchAll(); ?>
<div class="card card-body table-responsive"><table class="table table-bordered"><thead><tr><th>Hari</th><th>Mulai</th><th>Selesai</th><th>Keterangan</th></tr></thead><tbody>
<?php foreach($rows as $r): ?><tr><td><?=e($r['hari'])?></td><td><?=e($r['jam_mulai'])?></td><td><?=e($r['jam_selesai'])?></td><td><?=e($r['keterangan'])?></td></tr><?php endforeach; ?>
</tbody></table></div><?php endif; ?>
