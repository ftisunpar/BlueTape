<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!doctype html>
<html class="no-js" lang="en">
    <?php $this->load->view('templates/head_loggedin'); ?>
    <body>
        <?php $this->load->view('templates/topbar_loggedin'); ?>
        <?php $this->load->view('templates/flashmessage'); ?>

        <div class="row">
            <div class="medium-12 column">
                <div class="callout">
                    <h5>Permohonan Baru</h5>
                    <?php if ($submitAllowed === TRUE): ?>
                        <form method="POST" action="/TranskripRequest/add">
                            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>" />
                            <div class="row">
                                <div class="large-4 column">
                                    <label>Yang memohon:
                                        <input type="email" name="requestByEmail" value="<?= $requestByEmail ?>" readonly="true"/>
                                    </label>
                                </div>
                                <div class="large-4 column">
                                    <label>NPM:
                                        <input type="text" value="<?= $requestByNPM ?>" readonly="true"/>
                                    </label>
                                </div>
                                <div class="large-4 column">
                                    <label>Nama:
                                        <input type="text" name="requestByName" value="<?= $requestByName ?>" readonly="true"/>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="large-12 column">
                                    <label>Keperluan:
                                        <input type="text" name="requestUsage" required/>
                                    </label>
                                </div>
                            </div>
                            <input type="submit" class="button" value="Kirim Permohonan">
                        </form>
                    <?php else: ?>
                        <div class="alert callout"><?= $submitAllowed ?></div>
                    <?php endif ?>
                </div>
                <div class="callout">
                    <h5>Histori Permohonan</h5>
                    <table class="stack">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Status</th>
                                <th>Tanggal Permohonan</th>
                                <th>Tanggal Jawab/Cetak</th>
                                <th>Keterangan</th>
                                <th>Detail...</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($requests as $request): ?>
                                <tr>
                                    <td><?= $request->id ?></td>
                                    <td><span class="<?= $request->labelClass ?> label"><?= $request->status ?></span></td>
                                    <td><time datetime="<?= $request->requestDateTime ?>"><?= $request->requestDateString ?></time></td>
                                    <td><time datetime="<?= $request->answeredDateTime ?>"><?= $request->answeredDateString ?></td>
                                    <td><?= $request->answeredMessage ?></td>
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