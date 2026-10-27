<?php
require_once __DIR__.'/session.php';
require_once __DIR__.'/koneksi.php';
require_once __DIR__.'/helper.php';
require_login();
if($_SERVER['REQUEST_METHOD']!=='POST') {http_response_code(405);exit('Metode tidak diizinkan.');}
check_csrf();
$entity=(string)($_POST['entity']??'');
$action=(string)($_POST['action']??'save');
$schema=[
 'guru'=>['table'=>'guru','cols'=>['nip','nama','mapel','no_hp'],'required'=>['nama']],
 'user'=>['table'=>'user','cols'=>['guru_id','nama','username','role'],'required'=>['nama','username','role']],
 'jadwal'=>['table'=>'jadwal_piket','cols'=>['guru_id','hari','jam_mulai','jam_selesai','keterangan'],'required'=>['guru_id','hari','jam_mulai','jam_selesai']],
 'absensi'=>['table'=>'absensi_piket','cols'=>['guru_id','tanggal','status','keterangan'],'required'=>['guru_id','tanggal','status']],
 'tamu'=>['table'=>'tamu','cols'=>['tanggal','nama','instansi','tujuan','bertemu'],'required'=>['tanggal','nama','tujuan']],
 'laporan'=>['table'=>'laporan_piket','cols'=>['guru_id','tanggal','kegiatan','kejadian','tindak_lanjut'],'required'=>['guru_id','tanggal','kegiatan']]
];
if(!isset($schema[$entity])) {http_response_code(400);exit('Data tidak dikenal.');}
if(in_array($entity,['guru','user','jadwal'],true)) require_admin();
if(!admin() && my_guru_id()===0 && in_array($entity,['absensi','laporan'],true)){
    flash('Akun belum terhubung dengan data guru.','danger');go($entity);
}
$s=$schema[$entity];$table=$s['table'];$id=filter_var($_POST['id']??0,FILTER_VALIDATE_INT)?:0;
try {
    if($action==='delete'){
        if($entity==='user'){
            if($id===(int)$_SESSION['user']['id']) throw new RuntimeException('Akun yang sedang digunakan tidak boleh dihapus.');
            $check=$pdo->prepare("SELECT role FROM user WHERE id=?");
            $check->execute([$id]);
            if($check->fetchColumn()==='admin' && (int)$pdo->query("SELECT COUNT(*) FROM user WHERE role='admin'")->fetchColumn()<=1)
                throw new RuntimeException('Minimal satu akun admin harus tersedia.');
        }
        if($id<1) throw new RuntimeException('ID tidak valid.');
        if(!admin() && in_array($entity,['absensi','laporan'],true)){
            $q=$pdo->prepare("DELETE FROM $table WHERE id=? AND guru_id=?");
            $q->execute([$id,my_guru_id()]);
        } elseif(!admin() && $entity==='tamu'){
            $q=$pdo->prepare("DELETE FROM $table WHERE id=? AND dicatat_oleh=?");
            $q->execute([$id,$_SESSION['user']['id']]);
        } else {
            $q=$pdo->prepare("DELETE FROM $table WHERE id=?");$q->execute([$id]);
        }
        if($q->rowCount()===0) throw new RuntimeException('Data tidak ditemukan atau tidak diizinkan.');
        flash('Data berhasil dihapus.');go($entity);
    }
    $data=[];
    foreach($s['cols'] as $col){
        $v=trim((string)($_POST[$col]??''));
        $data[$col]=$v===''?null:$v;
    }
    if(!admin() && in_array($entity,['absensi','laporan'],true)) $data['guru_id']=my_guru_id();
    foreach($s['required'] as $col) if($data[$col]===null) throw new RuntimeException("Kolom $col wajib diisi.");
    if(!in_array($action,['save','delete'],true)) throw new RuntimeException('Aksi tidak valid.');
    foreach(['tanggal'] as $dateCol){
        if(isset($data[$dateCol])){
            $date=DateTime::createFromFormat('!Y-m-d',$data[$dateCol]);
            if(!$date || $date->format('Y-m-d')!==$data[$dateCol]) throw new RuntimeException('Tanggal tidak valid.');
        }
    }
    if($entity==='jadwal' && $data['jam_selesai']<=$data['jam_mulai']) throw new RuntimeException('Jam selesai harus setelah jam mulai.');
    if($entity==='user'){
        if($id && $data['role']!=='admin'){
            $q=$pdo->prepare("SELECT role FROM `user` WHERE id=?");$q->execute([$id]);
            if($q->fetchColumn()==='admin' && (int)$pdo->query("SELECT COUNT(*) FROM `user` WHERE role='admin'")->fetchColumn()<=1)
                throw new RuntimeException('Minimal satu akun admin harus tersedia.');
            if($id===(int)$_SESSION['user']['id']) throw new RuntimeException('Role akun yang sedang digunakan tidak boleh diturunkan.');
        }
        if(!in_array($data['role'],['admin','guru'],true)) throw new RuntimeException('Role tidak valid.');
        if($data['role']==='guru' && !$data['guru_id']) throw new RuntimeException('Pilih guru untuk akun guru.');
    }
    if($entity==='absensi' && !in_array($data['status'],['Hadir','Izin','Sakit','Alpa'],true)) throw new RuntimeException('Status tidak valid.');
    if($entity==='jadwal' && !in_array($data['hari'],['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'],true)) throw new RuntimeException('Hari tidak valid.');
    if($entity==='tamu' && !$id) $data['dicatat_oleh']=$_SESSION['user']['id'];
    if($entity==='user'){
        $pass=(string)($_POST['password']??'');
        if(!$id && strlen($pass)<8) throw new RuntimeException('Password minimal 8 karakter.');
        if($pass!==''){
            if(strlen($pass)<8) throw new RuntimeException('Password minimal 8 karakter.');
            $data['password']=password_hash($pass,PASSWORD_DEFAULT);
        }
    }
    if($id){
        $where='id=?';$params=array_values($data);$params[]=$id;
        if(!admin() && in_array($entity,['absensi','laporan'],true)){
            $where.=' AND guru_id=?';$params[]=my_guru_id();
        }
        if(!admin() && $entity==='tamu'){
            $where.=' AND dicatat_oleh=?';$params[]=$_SESSION['user']['id'];
        }
        $set=implode(',',array_map(fn($c)=>"$c=?",array_keys($data)));
        $q=$pdo->prepare("UPDATE $table SET $set WHERE $where");$q->execute($params);
    } else {
        $cols=array_keys($data);
        $q=$pdo->prepare('INSERT INTO '.$table.' ('.implode(',',$cols).') VALUES ('.implode(',',array_fill(0,count($cols),'?')).')');
        $q->execute(array_values($data));
    }
    flash('Data berhasil disimpan.');
} catch(Throwable $e){
    $msg=$e->getMessage();
    if($e instanceof PDOException){
        $code=(int)($e->errorInfo[1]??0);
        $msg=$code===1062?'Data sudah ada. Username/NIP harus unik; absensi hanya sekali per guru per tanggal.':($code===1452?'Guru atau pengguna terkait tidak ditemukan. Pilih data yang tersedia.':'Gagal menyimpan. Periksa struktur database sesuai database/db_piketguru.sql.');
    }
    flash($msg,'danger');
}
go($entity);
