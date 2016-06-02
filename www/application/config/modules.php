<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$config['module-names'] = array(
    'TranskripRequest' => 'Permohonan Cetak Transkrip',
    'TranskripManage' => 'Manajemen Cetak Transkrip'
);

$config['modules'] = array(
    'TranskripRequest' => array('root', 'mahasiswa.ftis'),
    'TranskripManage' => array('root', 'tu.ftis')
);

$config['roles'] = array(
    'root' => array('pascal@unpar.ac.id', 'shao.wei@unpar.ac.id'),
    'tu.ftis' => 'shao\\.wei@unpar\\.ac\\.id',
    'mahasiswa.ftis' => '7[123]\\d{5}@student\\.unpar\\.ac\\.id'
);