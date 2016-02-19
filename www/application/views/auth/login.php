<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
    <head>
        <title>Login</title>
    </head>
    <body>
        <h1>Selamat datang di BlueTape</h1>
        <?php $this->load->view('templates/flashmessage'); ?>
        <a href="<?= $authURL; ?>" role="button">Login dengan Google</a> (gunakan akun @unpar.ac.id atau @student.unpar.ac.id Anda)
    </body>
</html>