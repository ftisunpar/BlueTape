<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$config['module-names'] = array(
    'TranskripRequest' => 'Cetak Transkrip',
    'TranskripManage' => 'Manajemen Cetak Transkrip',
    'PerubahanKuliahRequest' => 'Perubahan Kuliah',
    'PerubahanKuliahManage' => 'Manajemen Perubahan Kuliah',
    'EntriJadwalDosen' => 'Entri Jadwal Dosen',
    'LihatJadwalDosen' => 'Lihat Jadwal Dosen'    
);

$config['modules'] = array(
    'TranskripRequest' => array('root', 'mahasiswa.ftis'),
    'TranskripManage' => array('root', 'tu.ftis'),
    'PerubahanKuliahRequest' => array('root', 'staf.unpar'),
    'PerubahanKuliahManage' => array('root', 'tu.ftis'),
    'EntriJadwalDosen' => array('root', 'dosen.informatika' ),
    'LihatJadwalDosen' => array('root', 'mahasiswa.informatika', 'dosen.informatika')
);

$config['roles'] = array(
    'root' => array('pascal@unpar.ac.id', 'shao.wei@unpar.ac.id','stephenhadi123@gmail.com','skripsimailbtp@gmail.com'),
    'tu.ftis' => array('shao.wei@unpar.ac.id', 'purnomo@unpar.ac.id', 'walip@unpar.ac.id'),
    'mahasiswa.ftis' => '(7[123]\\d{5})|(20[1-9][0-9]7[123][0-9]{4})|(61[678][0-9]{7})@student\\.unpar\\.ac\\.id',
    'staf.unpar' => '.+@unpar\\.ac\\.id',
    'dosen.informatika' => array ('cheni@unpar.ac.id', 'mariskha@unpar.ac.id', 'anung@unpar.ac.id', 'moertini@unpar.ac.id', 'natalia@unpar.ac.id', 'chandraw@unpar.ac.id', 'elisatih@unpar.ac.id', 'gkarya@unpar.ac.id', 'husnulhakim@unpar.ac.id', 'joanna@unpar.ac.id', 'lionov@unpar.ac.id', 'luciana@unpar.ac.id', 'pascal@unpar.ac.id', 'rosad5@unpar.ac.id', 'vania.natali@unpar.ac.id', 'kristopher.h@unpar.ac.id', 'raymond.chandra@unpar.ac.id', 'keenan.leman@unpar.ac.id', 'ignasiuserwin@unpar.ac.id'),
    'mahasiswa.informatika' => '(73\\d{5}|(20[1-9][0-9]73[0-9]{4})|618\\d{7})@student\\.unpar\\.ac\\.id'
);
