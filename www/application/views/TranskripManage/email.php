<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->helper('url');
?><!doctype html>
<html>
    <body>
        <p>Yth <?= $name ?>,</p>
        <p>E-mail ini untuk memberitahukan bahwa permohonan
            pencetakan transkrip baru anda sudah ditanggapi. Silahkan
            masuk ke <a href="<?= base_url() ?>">BlueTape</a> untuk
            melihat keterangan lebih lanjut dari permohonan anda.</p>
        <p>Terima kasih,<br/>Sistem BlueTape</p>
        <p>Catatan: e-mail ini dikirimkan secara otomatis oleh sistem.</p>
    </body>
</html>