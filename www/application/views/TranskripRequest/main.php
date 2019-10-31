<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!doctype html>
<html class="no-js" lang="en">
    <?php $this->load->view('templates/head_loggedin'); ?>
    <body>
        <?php $this->load->view('templates/topbar_loggedin'); ?>
        <?php $this->load->view('templates/flashmessage'); ?>

        <div class="container">
            <div class="row ">
                <div class="col">
                    <div class="p-3 border">
                        <h5>Permohonan Baru</h5>
                        <?php if (is_array($forbiddenTypes)): ?>
                            <form method="POST" action="/TranskripRequest/add">
                                <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>" />
                                <div class="row">
                                    <div class="large-4 column">
                                        <label>Yang memohon:
                                            <input type="email" name="requestByEmail" value="<?= $requestByEmail ?>" readonly="readonly"/>
                                        </label>
                                    </div>
                                    <div class="large-4 column">
                                        <label>NPM:
                                            <input type="text" value="<?= $requestByNPM ?>" readonly="readonly"/>
                                        </label>
                                    </div>
                                    <div class="large-4 column">
                                        <label>Nama:
                                            <input type="text" name="requestByName" value="<?= $requestByName ?>" readonly="readonly"/>
                                        </label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="large-4 column">
                                        <label>Tipe Transkrip:
                                            <select name="requestType">
                                                <?php foreach (Transkrip_model::REQUEST_TYPES as $type => $name): ?>
                                                    <?php if (!in_array($type, $forbiddenTypes)): ?>
                                                        <option value="<?= $type ?>"><?= $name ?></option>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </select>
                                        </label>
                                    </div>
                                    <div class="large-8 column">
                                        <label>Keperluan:
                                            <input type="text" name="requestUsage" required/>
                                        </label>
                                    </div>
                                </div>
                                <input type="submit" class="button" value="Kirim Permohonan">
                            </form>
                        <?php else: ?>
                            <p>&nbsp;</p>
                            <?= $forbiddenTypes ?>
                        <?php endif ?>
                    </div>
                    <br>
                    <div class="p-3 border">
                        <h5>Histori Permohonan</h5>
                        <table class="table ">
                            <thead>
                            <tr>
                                <th class="table-active">ID</th>
                                <th class="table-active">Status</th>
                                <th class="table-active">Tanggal Permohonan</th>
                                <th class="table-active">Tipe Transkrip</th>
                                <th class="table-active">Tanggal Jawab/Cetak</th>
                                <th class="table-active">Keterangan</th>
                                <th class="table-active">Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($requests as $request): ?>
                                <tr>
                                    <th>#<?= $request->id ?></th>
                                    <td><span class="<?= $request->labelClass ?> label"><?= $request->status ?></span></td>
                                    <td><time datetime="<?= $request->requestDateTime ?>"><?= $request->requestDateString ?></time></td>
                                    <td><?= $request->requestType ?></td>
                                    <td><time datetime="<?= $request->answeredDateTime ?>"><?= $request->answeredDateString ?></td>
                                    <td><?= $request->answeredMessage ?></td>
                                    <td>
                                        <!-- Button trigger modal -->
                                        <a data-toggle="modal" data-target="#lihatModal" id="detail<?= $request->id ?>">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <!-- Modal -->
                                        <div class="modal fade" id="lihatModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLongTitle">Detail Permohonan #<?= $request->id ?></h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <table class="table ">
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
                                                                <th>Tipe Transkrip</th>
                                                                <td><?= $request->requestType ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th>Keperluan</th>
                                                                <td><?= $request->requestUsage ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="col">Jawaban</th>
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
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <?php $this->load->view('templates/script_foundation'); ?>
    </body>
</html>