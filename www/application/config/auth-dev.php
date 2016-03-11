<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$config['domain'] = 'http://localhost';
$config['google-clientid'] = 'FILLME';
$config['google-clientsecret'] = 'FILLME';
$config['google-redirecturi'] = $config['domain'] . '/auth/oauth2callback';

$config['roles'] = array(
    'root' => 'pascal@unpar\\.ac\\.id',
    'tu.ftis' => '(shao\\.wei)@unpar\\.ac\\.id',
    'mahasiswa.ftis' => '7[123]\\d{5}@student\\.unpar\\.ac\\.id'
);

$config['modules'] = array(
    'TranskripRequest' => array('root', 'mahasiswa.ftis'),
    'TranskripManage' => array('root', 'tu.ftis')
);
