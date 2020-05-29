<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$config['domain'] = 'http://127.0.0.1';
$config['google-clientid'] = '507631469463-g3bhd98g1ah4m1tt23c7t3vamg8lr72t.apps.googleusercontent.com';
$config['google-clientsecret'] = 'Qi0Y_kL-GFFRAZvEqfzJLUie';
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