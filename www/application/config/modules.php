<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$config['module-names'] = array(
    'TranskripRequest' => 'Cetak Transkrip',
    'TranskripManage' => 'Manajemen Cetak Transkrip',
    'PerubahanKuliahRequest' => 'Perubahan Kuliah',
    'PerubahanKuliahManage' => 'Manajemen Perubahan Kuliah'
);

$config['modules'] = array(
    'TranskripRequest' => array('root', 'mahasiswa.ftis'),
    'TranskripManage' => array('root', 'tu.ftis'),
    'PerubahanKuliahRequest' => array('root', 'staf.unpar'),
    'PerubahanKuliahManage' => array('root', 'tu.ftis'));

$config['roles'] = array(
    'root' => array('pascal@unpar.ac.id', 'shao.wei@unpar.ac.id'),
    'tu.ftis' => array('shao.wei@unpar.ac.id', 'pranyoto@unpar.ac.id'),
    'mahasiswa.ftis' => '7[123]\\d{5}@student\\.unpar\\.ac\\.id',
    'staf.unpar' => '.+@unpar\\.ac\\.id'
);
