<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!doctype html>
<html class="no-js" lang="en">
    <?php $this->load->view('templates/head_loggedin'); ?>
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
                            <th>Detail...</th>
                            <th>Tolak...</th>
                            <th>Cetak...</th>
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
                                    <a data-open="detail<?= $request->id ?>">DETAIL</a>
                                </td>
                                <td>
                                    <div class="reveal" id="tolak<?= $request->id ?>" data-reveal>
                                        <h5>Tolak Permohonan</h5>
                                        <form method="POST" action="/TranskripManage/answer">
                                            <input type="hidden" name="id" value="<?= $request->id ?>"/>
                                            <input type="hidden" name="answer" value="rejected"/>
                                            <label>Email penjawab:
                                                <input type="text" value="<?= $answeredByEmail ?>" readonly="true"/>
                                            </label>
                                            <label>Alasan penolakan:
                                                <input name="answeredMessage" class="input-group-field" type="text" required/>
                                            </label>
                                            <input type="submit" class="alert button" value="Tolak"/>
                                        </form>
                                        <button class="close-button" data-close aria-label="Tutup" type="button">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <a data-open="tolak<?= $request->id ?>">TOLAK</a>
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
                                            <input type="hidden" name="id" value="<?= $request->id ?>"/>
                                            <input type="hidden" name="answer" value="printed"/>
                                            <label>Email penjawab:
                                                <input type="text" value="<?= $answeredByEmail ?>" readonly="true"/>
                                            </label>
                                            <label>Keterangan tambahan:
                                                <input name="answeredMessage" class="input-group-field" type="text" required/>
                                            </label>
                                            <input type="submit" class="alert button" value="Cetak"/>
                                        </form>
                                        <button class="close-button" data-close aria-label="Tutup" type="button">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <a data-open="cetak<?= $request->id ?>">CETAK</a>
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