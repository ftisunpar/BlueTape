<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$config['domain'] = 'http://localhost';
$config['google-clientid'] = NULL;
$config['google-clientsecret'] = NULL;
$config['google-redirecturi'] = $config['domain'] . '/auth/oauth2callback';

$config['email-config'] = Array(
    'protocol' => NULL,
    'smtp_host' => NULL,
    'smtp_port' => NULL,
    'smtp_user' => NULL,
    'smtp_pass' => NULL,
    'mailtype' => NULL,
    'charset' => NULL
);