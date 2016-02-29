<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!doctype html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title><?= $currentModule ?></title>
        <link rel="stylesheet" href="/public/foundation-6/css/foundation.css" />
        <link rel="stylesheet" href="/public/foundation-6/css/app.css" />
    </head>
    <body>
        <?php $this->load->view('templates/topbar_loggedin'); ?>
        <?php $this->load->view('templates/flashmessage'); ?>

        <div class="row">
            <div class="callout">
                <h5>Permintaan Transkrip</h5>
                <table class="scroll">
                    <thead>
                        <tr>
                            <th>Informasi Permintaan</th>
                            <th>Informasi Jawaban</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($requests as $request): ?>
                            <tr>
                                <td>
                                    <strong>Tanggal:</strong> <time datetime="<?= $request->requestDateTime ?>"><?= $request->requestDateTime ?></time><br/>
                                    <strong>Email:</strong> <?= $request->requestByEmail ?><br/>
                                    <strong>NPM:</strong> <?= $this->bluetape->emailToNPM($request->requestByEmail, 'tidak ada') ?><br/>
                                    <strong>Nama:</strong><?= $request->requestByName ?><br/>
                                    <strong>Keperluan:</strong> <?= $request->requestUsage ?>
                                </td>
                                <td>
                                    <?php if ($request->answeredDateTime === NULL): ?>
                                        -
                                    <?php else: ?>
                                        <strong>Tanggal:</strong> <time datetime="<?= $request->answeredDateTime ?>"><?= $request->answeredDateTime ?><br/>
                                        <strong>Email:</strong> <?= $request->answeredByEmail ?><br/>
                                        <strong>Pesan:</strong> <q><?= $request->answeredMessage ?></q><br/>
                                    <?php endif ?>
                                </td>
                                <td><span class="<?= $request->labelClass ?> label"><?= $request->status ?></span></td>
                                <td>
                                    <form action="/TranskripManage/answer" method="POST">
                                        <button action="submit" name="action" value="print" class="success button">Cetak</button>
                                        <button action="submit" name="action" value="reject" class="alert button">Tolak</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="/public/foundation-6/js/vendor/jquery.min.js"></script>
    <script src="/public/foundation-6/js/vendor/what-input.min.js"></script>
    <script src="/public/foundation-6/js/foundation.min.js"></script>
    <script src="/public/foundation-6/js/app.js"></script>
</body>
</html>