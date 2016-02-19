<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$config['domain'] = 'http://localhost';
$config['google-clientid'] = 'FILLME';
$config['google-clientsecret'] = 'FILLME';
$config['google-redirecturi'] = $config['domain'] . '/auth/oauth2callback';

$config['roles'] = array(
    'tu.ftis' => '(pascal|shao\\.wei)@unpar\\.ac\\.id',
    'mahasiswa.ftis' => '7[123]\\d{5}@student\\.unpar\\.ac\\.id'
);

$config['modules'] = array(
    'TranskripRequest' => 'mahasiswa.ftis',
    'TranskripManage' => 'tu.ftis'
);
