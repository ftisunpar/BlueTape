<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$config['domain'] = 'http://localhost';
//$config['domain'] = 'http://127.0.0.1/';
$config['google-clientid'] = '755661919348-3b2u44e804efh2mghpadttnqh3u4ujd9.apps.googleusercontent.com';
$config['google-clientsecret'] = '4dAVtOJPlTaFEkm3RbwBY7Vw';
$config['google-redirecturi'] = $config['domain'] . '/auth/oauth2callback';

$config['email-config'] = Array(
    'protocol' => 'smtp',
    'smtp_host' => 'ssl://smtp.googlemail.com',
    'smtp_port' => 465,
    'smtp_user' => 'xxx',
    'smtp_pass' => 'xxx',
    'mailtype' => 'html',
    'charset' => 'iso-8859-1'
);
