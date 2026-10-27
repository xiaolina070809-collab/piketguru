<?php
require_login();
$config=[
 'guru'=>['title'=>'Data Guru','table'=>'guru','fields'=>['nip'=>'NIP','nama'=>'Nama Guru','mapel'=>'Mata Pelajaran','no_hp'=>'No. HP']],
 'user'=>['title'=>'Pengguna','table'=>'user','fields'=>['guru_id'=>'Guru','nama'=>'Nama','username'=>'Username','role'=>'Role']],
 'jadwal'=>['title'=>'Jadwal Piket','table'=>'jadwal_piket','fields'=>['guru_id'=>'Guru','hari'=>'Hari','jam_mulai'=>'Jam Mulai','jam_selesai'=>'Jam Selesai','keterangan'=>'Keterangan']],
 'absensi'=>['title'=>'Absensi Piket','table'=>'absensi_piket','fields'=>['guru_id'=>'Guru','tanggal'=>'Tanggal','status'=>'Status','keterangan'=>'Keterangan']],
 'tamu'=>['title'=>'Data Tamu','table'=>'tamu','fields'=>['tanggal'=>'Tanggal','nama'=>'Nama Tamu','instansi'=>'Instansi','tujuan'=>'Tujuan','bertemu'=>'Bertemu']],
 'laporan'=>['title'=>'Laporan Piket','table'=>'laporan_piket','fields'=>['guru_id'=>'Guru','tanggal'=>'Tanggal','kegiatan'=>'Kegiatan','kejadian'=>'Kejadian','tindak_lanjut'=>'Tindak Lanjut']]
];
if(!isset($config[$halaman])){http_response_code(404);include __DIR__.'/errors/404.php';return;}
if(in_array($halaman,['guru','user','jadwal'],true))require_admin();
$c=$config[$halaman];$table=$c['table'];
$guruRows=$pdo->query('SELECT id,nama FROM guru ORDER BY nama')->fetchAll();
$guruNames=array_column($guruRows,'nama','id');
$where='';$params=[];
if(!admin() && in_array($halaman,['absensi','laporan'],true)){
    $where=' WHERE guru_id=?';$params=[my_guru_id()];
}
if(!admin() && $halaman==='tamu'){$where=' WHERE dicatat_oleh=?';$params=[$_SESSION['user']['id']];}
$q=$pdo->prepare("SELECT * FROM $table $where ORDER BY id DESC LIMIT 500");$q->execute($params);$rows=$q->fetchAll();
$editId=filter_input(INPUT_GET,'edit',FILTER_VALIDATE_INT)?:0;$edit=null;
if($editId){
    $q=$pdo->prepare("SELECT * FROM $table WHERE id=?".($where?str_replace(' WHERE ',' AND ',$where):''));
    $q->execute(array_merge([$editId],$params));$edit=$q->fetch()?:null;
}
$canForm=admin() || $halaman==='tamu' || my_guru_id()>0;
?>
<div class="d-flex justify-content-between align-items-center mb-3"><h2><?=e($c['title'])?></h2>
<?php if($canForm): ?><a class="btn btn-primary" href="#formdata">+ Tambah Data</a><?php endif; ?></div>
<?php if($canForm): ?>
<div class="card card-primary card-outline" id="formdata"><div class="card-header"><strong><?= $edit?'Edit':'Tambah' ?> <?=e($c['title'])?></strong></div>
<div class="card-body"><form method="post" action="proses/proses<?=e($halaman)?>.php">
<?=csrf_field()?><input type="hidden" name="entity" value="<?=e($halaman)?>">
<input type="hidden" name="id" value="<?=e($edit['id']??0)?>">
<div class="row">
<?php foreach($c['fields'] as $key=>$label):
$value=$edit[$key]??'';
if(!admin() && $key==='guru_id')continue;
?>
<div class="form-group col-md-6"><label for="f_<?=e($key)?>"><?=e($label)?></label>
<?php if($key==='guru_id'): ?>
<select class="form-control" name="guru_id" id="f_guru_id" <?=in_array($halaman,['jadwal','absensi','laporan'],true)||$halaman==='user'?'':' '?>>
<option value="">-- Pilih Guru --</option>
<?php foreach($guruRows as $g): ?><option value="<?=$g['id']?>" <?=((string)$value===(string)$g['id'])?'selected':''?>><?=e($g['nama'])?></option><?php endforeach; ?></select>
<?php elseif($key==='role' || $key==='hari' || $key==='status'):
$options=$key==='role'?['admin','guru']:($key==='hari'?['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu']:['Hadir','Izin','Sakit','Alpa']); ?>
<select class="form-control" name="<?=e($key)?>" id="f_<?=e($key)?>" required><option value="">-- Pilih --</option>
<?php foreach($options as $opt): ?><option value="<?=e($opt)?>" <?=$value===$opt?'selected':''?>><?=e(ucfirst($opt))?></option><?php endforeach; ?></select>
<?php elseif(in_array($key,['kegiatan','kejadian','tindak_lanjut','keterangan'],true)): ?>
<textarea class="form-control" name="<?=e($key)?>" id="f_<?=e($key)?>" rows="2"><?=e($value)?></textarea>
<?php else:
$type=$key==='tanggal'?'date':(str_starts_with($key,'jam_')?'time':'text');
if($type==='time' && $value)$value=substr($value,0,5);
if($key==='tanggal' && !$value)$value=date('Y-m-d');
?>
<input class="form-control" type="<?=$type?>" name="<?=e($key)?>" id="f_<?=e($key)?>" value="<?=e($value)?>" <?=in_array($key,['nama','username','tanggal','tujuan','jam_mulai','jam_selesai'],true)?'required':''?>>
<?php endif; ?></div>
<?php endforeach; ?>
<?php if($halaman==='user'): ?><div class="form-group col-md-6"><label>Password <?= $edit?'(kosongkan jika tidak diganti)':'(minimal 8 karakter)' ?></label>
<input class="form-control" type="password" name="password" <?=$edit?'':'required minlength="8"'?> autocomplete="new-password"></div><?php endif; ?>
</div><button class="btn btn-success">Simpan</button>
<?php if($edit): ?><a class="btn btn-secondary" href="<?=url($halaman)?>">Batal Edit</a><?php endif; ?>
</form></div></div>
<?php elseif(!admin() && $halaman!=='tamu'): ?>
<div class="alert alert-warning">Akun belum terhubung ke data guru. Hubungi admin.</div>
<?php endif; ?>
<div class="card"><div class="card-body table-responsive">
<table class="table table-striped table-bordered table-sm"><thead><tr><th>No</th>
<?php foreach($c['fields'] as $label): ?><th><?=e($label)?></th><?php endforeach; ?><th>Aksi</th></tr></thead><tbody>
<?php foreach($rows as $i=>$r): ?><tr><td><?=$i+1?></td>
<?php foreach($c['fields'] as $key=>$label): ?><td><?=e($key==='guru_id'?($guruNames[$r[$key]]??'-'):$r[$key])?></td><?php endforeach; ?>
<td style="min-width:140px">
<a class="btn btn-warning btn-sm" href="<?=url($halaman)?>&edit=<?=$r['id']?>#formdata">Edit</a>
<form method="post" action="proses/proses<?=e($halaman)?>.php" class="d-inline" onsubmit="return confirm('Hapus data ini?')">
<?=csrf_field()?><input type="hidden" name="entity" value="<?=e($halaman)?>">
<input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?=$r['id']?>">
<button class="btn btn-danger btn-sm">Hapus</button></form></td></tr><?php endforeach; ?>
<?php if(!$rows): ?><tr><td colspan="<?=count($c['fields'])+2?>" class="text-center">Belum ada data.</td></tr><?php endif; ?>
</tbody></table></div></div>
