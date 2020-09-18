<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!doctype html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Login</title>
        <link rel="stylesheet" href="/public/lib/css/bootstrap.css" />
        <link rel="stylesheet" href="/public/lib/css/bootstrap-grid.css" />
        <link rel="stylesheet" href="/public/lib/css/bootstrap-reboot.css" />
        <link rel="stylesheet" href="/public/lib/fontawesome/fontawesome.css" />
    </head>
    <body>
        <?php $this->load->view('templates/flashmessage'); ?>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <p class="text-center">
                        <img src="/public/img/logo.png" alt="Logo BlueTape"/><br/><br/>
                        Silahkan login di aplikasi BlueTape, dengan menekan tombol di bawah ini. Gunakan akun
                        <b>@unpar.ac.id</b> atau <b>@student.unpar.ac.id</b>.<br/><br/>
                        <a href="<?= $authURL; ?>" class="btn btn-primary btn-lg">Login dengan Google</a><br/><br/>
                        <a href="https://github.com/ftisunpar/BlueTape/wiki/UserGuide" target="_blank">Petunjuk Penggunaan</a>
                    </p>
                </div>
            </div>
        </div>
        <?php $this->load->view('templates/script_foundation'); ?>
    </body>
</html>