<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!doctype html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Login</title>
        <link rel="stylesheet" href="/public/foundation-6/css/foundation.css" />
        <link rel="stylesheet" href="/public/foundation-6/css/app.css" />
    </head>
    <body>
        <?php $this->load->view('templates/flashmessage'); ?>
        <div class="row">
            <div class="large-3 columns">&nbsp;</div>
            <div class="large-6 columns centered">
                <img src="/public/images/logo.png" alt="Logo BlueTape"/>
                <p>Silahkan login di aplikasi BlueTape, dengan menekan tombol di bawah ini. Gunakan akun
                    <b>@unpar.ac.id</b> atau <b>@student.unpar.ac.id</b>.</p>
                <a href="<?= $authURL; ?>" class="button">Login dengan Google</a><br/><br/>
                <a href="https://github.com/ftisunpar/BlueTape/wiki/UserGuide" target="_blank">Petunjuk Penggunaan</a>
            </div>
            <div class="large-3 columns">&nbsp;</div>
        </div>

        <script src="/public/foundation-6/js/vendor/jquery.min.js"></script>
        <script src="/public/foundation-6/js/vendor/what-input.min.js"></script>
        <script src="/public/foundation-6/js/foundation.min.js"></script>
        <script src="/public/foundation-6/js/app.js"></script>
    </body>
</html>