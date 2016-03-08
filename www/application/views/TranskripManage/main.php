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
                <table class="stack">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Status</th>
                            <th>Tanggal Permohonan</th>
                            <th>NPM</th>
                            <th>Lihat Detail</th>
                            <th>Tolak Permohonan</th>
                            <th>Cetak Permohonan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($requests as $request): ?>
                            <tr>
                                <td><?= $request->id ?></td>
                                <td><span class="<?= $request->labelClass ?> label"><?= $request->status ?></span></td>
                                <td><time datetime="<?= $request->requestDateTime ?>"><?= $request->requestDateTime ?></time></td>
                                <td><?= $this->bluetape->emailToNPM($request->requestByEmail, 'tidak ada') ?></td>
                                <td>
                                    <div class="reveal" id="detail<?= $request->id ?>" data-reveal>
                                        <h5>Detail Permohonan</h5>
                                        <table class="stack">
                                            <tbody>
                                                <tr>
                                                    <th>E-mail Pemohon</th>
                                                    <td><?= $request->requestByEmail ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Nama Pemohon</th>
                                                    <td><?= $request->requestByName ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Tanggal Permohonan</th>
                                                    <td><?= $request->requestDateTime ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Keperluan</th>
                                                    <td><?= $request->requestUsage ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Jawaban</th>
                                                    <td><?= $request->answer ?></td>
                                                </tr>
                                                <tr>
                                                    <th>E-mail Penjawab</th>
                                                    <td><?= $request->answeredByEmail ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Tanggal Dijawab</th>
                                                    <td><?= $request->answeredDateTime ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Keterangan Penjawab</th>
                                                    <td><?= $request->answeredMessage ?></td>   
                                                </tr>
                                            </tbody>
                                        </table>
                                        <button class="close-button" data-close aria-label="Tutup" type="button">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <a data-open="detail<?= $request->id ?>">detail</a>
                                </td>
                                <td>
                                    <div class="reveal" id="tolak<?= $request->id ?>" data-reveal>
                                        <h5>Tolak Permohonan</h5>
                                        <form method="POST" action="/TranskripManage/answer">
                                            <label>Alasan penolakan
                                                <div class="input-group">
                                                    <input class="input-group-field" type="text" required/>
                                                    <div class="input-group-button">
                                                        <input type="submit" class="alert button" name="action" value="Tolak">
                                                    </div>
                                                </div>
                                            </label>
                                        </form>
                                        <button class="close-button" data-close aria-label="Tutup" type="button">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <a data-open="tolak<?= $request->id ?>">tolak</a>
                                </td>
                                <td>
                                    <div class="reveal" id="cetak<?= $request->id ?>" data-reveal>
                                        <h5>Cetak Permohonan</h5>
                                        <?php if ($this->bluetape->emailToNPM($request->requestByEmail) !== NULL): ?>
                                            <a target="_blank" href="https://staf.admin.unpar/akademik/includes/cetak_kps.pre.php?sub=091403&content=cetak_kps&npm1=<?= $this->bluetape->emailToNPM($request->requestByEmail) ?>&npm2=&tahun_akd=&sem_akd=&file=&ttd_mhs=0&lang=id&stat_mhs=,A,C,S">Klik untuk membuka DPS</a>
                                        <?php else: ?>
                                            Link DPS tidak tersedia
                                        <?php endif ?>
                                        <form method="POST" action="/TranskripManage/answer">
                                            <label>Pesan khusus:
                                                <div class="input-group">
                                                    <input class="input-group-field" type="text" value="Silahkan mengambil di TU" required/>
                                                    <div class="input-group-button">
                                                        <input type="submit" class="success button" name="action" value="Cetak">
                                                    </div>
                                                </div>
                                            </label>
                                        </form>
                                        <button class="close-button" data-close aria-label="Tutup" type="button">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <a data-open="cetak<?= $request->id ?>">cetak</a>
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