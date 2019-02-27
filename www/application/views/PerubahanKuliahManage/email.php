<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->helper('url');
?><!doctype html>
<html>
    <body>
        <p>Yth <?= $requestByName ?>,</p>
        <p>E-mail ini untuk memberitahukan bahwa baru saja permohonan perubahan
            kuliah yang Anda ajukan dengan nomer #<?= $id ?> untuk mata kuliah
            <?= $mataKuliahCode ?> - <?= $mataKuliahName ?> telah berubah
            statusnya menjadi <strong><?= $answer ?></strong>
            <?php if ($answeredMessage !== ''): ?>
                dengan komentar &ldquo;<q><?= $answeredMessage ?></q>&rdquo;
            <?php endif; ?>
            .</p>
        <p>Terima kasih,<br/>Sistem BlueTape</p>
        <p>Catatan: e-mail ini dikirimkan secara otomatis oleh sistem.</p>
    </body>
</html>