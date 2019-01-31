<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$config['domain'] = 'http://localhost';
$config['google-clientid'] = "766749465484-lavk4oce3ta4hu2p1piekjtug02npkq0.apps.googleusercontent.com";
$config['google-clientsecret'] = "65xdbPMcfA26hzXbCDuQnD7X";
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