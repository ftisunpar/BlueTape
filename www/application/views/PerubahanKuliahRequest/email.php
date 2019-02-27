<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->helper('url');
?><!doctype html>
<html>
    <body>
        <p>Yth <?= $name ?>,</p>
        <p>E-mail ini untuk memberitahukan bahwa baru saja ada permohonan
            perubahan kuliah baru oleh <?= $requestByName ?>. Silahkan
            masuk ke <a href="<?= base_url() ?>">BlueTape</a> untuk
            menjawab permohonan ini.</p>
        <p>Terima kasih,<br/>Sistem BlueTape</p>
        <p>Catatan: e-mail ini dikirimkan secara otomatis oleh sistem.</p>
    </body>
</html>