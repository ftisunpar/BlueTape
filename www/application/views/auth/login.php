<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!doctype html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Login</title>
        <link rel="stylesheet" href="/public/css/foundation.css" />
        <link rel="stylesheet" href="/public/css/app.css" />
    </head>
    <body>
        <?php $this->load->view('templates/flashmessage'); ?>
        <div class="row">
            <div class="large-6 large-centered columns centered">
                <p class="text-center">
                    <img src="/public/img/logo.png" alt="Logo BlueTape"/><br/><br/>
                    Silahkan login di aplikasi BlueTape, dengan menekan tombol di bawah ini. Gunakan akun
                    <b>@unpar.ac.id</b> atau <b>@student.unpar.ac.id</b>.<br/><br/>
                    <a href="<?= $authURL; ?>" class="button expand">Login dengan Google</a><br/><br/>
                    <a class="text-center" href="https://github.com/ftisunpar/BlueTape/wiki/UserGuide" target="_blank">Petunjuk Penggunaan</a>
                </p>
            </div>
        </div>
        <?php $this->load->view('templates/script_foundation'); ?>
    </body>
</html>