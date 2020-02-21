<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!doctype html>
<html class="no-js" lang="en">
    <?php $this->load->view('templates/head_loggedin'); ?>
    <body>
        <?php $this->load->view('templates/topbar_loggedin'); ?>
        <?php $this->load->view('templates/flashmessage'); ?>

        <div class="container">
                <div class="card">
                    <div class="card-header">
                        Permintaan Transkrip
                    </div>
                    <div class="card-body">
                        <form method="GET" action="/TranskripManage">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Cari NPM:</span>
                                </div>

                                <input name="npm" class="form-control" type="text" placeholder="2013730013" maxlength="10" minlength="10"<?= $npmQuery === NULL ? '' : " value='$npmQuery'" ?>/>
                                <div class="input-group-append">
                                    <input class="btn btn-primary" type="submit" value="Cari"/>
                                </div>
                            </div>
                        </form>
                        <br>
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Status</th>
                                <th scope="col">Tanggal Permohonan</th>
                                <th scope="col">Tipe Transkrip</th>
                                <th scope="col">NPM</th>
                                <th scope="col">Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($requests as $request): ?>
                                <tr>
                                    <td>#<?= $request->id ?></td>
                                    <td><span class="badge badge-<?= $request->labelClass ?>"><?= $request->status ?></span></td>
                                    <td><time datetime="<?= $request->requestDateTime ?>"><?= $request->requestDateString ?></time></td>
                                    <td><?= $request->requestType ?></td>
                                    <td><?= isset($request->requestByNPM) ? $request->requestByNPM : '-' ?></td>
                                    <td>

                                        <div class="modal fade" id="detail<?= $request->id ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLongTitle">Detail Permohonan #<?= $request->id ?></h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <table class="table table-striped">
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
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <a data-toggle="modal" data-target="#detail<?= $request->id ?>" id="detailIkon<?= $request->id ?>">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <div class="modal fade" id="tolak<?= $request->id ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLongTitle">Tolak Permohonan #<?= $request->id ?></h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="POST" action="/TranskripManage/answer">
                                                            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>" />
                                                            <input type="hidden" name="id" value="<?= $request->id ?>"/>
                                                            <input type="hidden" name="answer" value="rejected"/>
                                                            <div class="form-group">
                                                                <label>Email penjawab:</label>
                                                                <input class="form-control" type="text" value="<?= $answeredByEmail ?>" readonly="true"/>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Alasan penolakan:</label>
                                                                <input class="form-control" name="answeredMessage" type="text" required/>
                                                            </div>
                                                            <p>&nbsp;</p>
                                                            <div class="form-group">
                                                                <input type="submit" class="btn btn-danger" value="Tolak"/>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <a data-toggle="modal" data-target="#tolak<?= $request->id ?>" id="detailIkon<?= $request->id ?>">
                                            <i class="fas fa-thumbs-down"></i>
                                        </a>

                                        <div class="modal fade" id="cetak<?= $request->id ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLongTitle">Cetak Permohonan #<?= $request->id ?></h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <?php if ($request->requestByNPM !== NULL): ?>
                                                            <a target="_blank" href="<?= sprintf($transkripURLs[$request->requestType], $request->requestByNPM) ?>">Klik untuk membuka DPS/LHS</a>
                                                        <?php else: ?>
                                                            Link DPS tidak tersedia
                                                        <?php endif ?>
                                                        <form method="POST" action="/TranskripManage/answer">
                                                            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>" />
                                                            <input type="hidden" name="id" value="<?= $request->id ?>"/>
                                                            <input type="hidden" name="answer" value="printed"/>
                                                            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>" />
                                                            <div class="form-group">
                                                                <label class="col-form-label">Email penjawab:</label>
                                                                <input class="form-control" type="text" value="<?= $answeredByEmail ?>" readonly="true"/>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-form-label">Keterangan tambahan:</label>
                                                                <input class="form-control" name="answeredMessage" type="text" required/>
                                                            </div>
                                                            <p>&nbsp;</p>
                                                            <div class="form-group">
                                                                <input class="btn btn-primary" type="submit" class="button" value="Sudah dicetak"/>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <a data-toggle="modal" data-target="#cetak<?= $request->id ?>" id="detailIkon<?= $request->id ?>">
                                            <i class="fas fa-print"></i>
                                        </a>

                                        <div class="modal fade" id="hapus<?= $request->id ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLongTitle">Hapus Permohonan #<?= $request->id ?></h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="POST" action="/TranskripManage/remove">
                                                            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>" />
                                                            <input type="hidden" name="id" value="<?= $request->id ?>"/>
                                                            <input type="hidden" name="answer" value="remove"/>
                                                            <p><strong>Yakin ingin menghapus?</strong></p>
                                                            <p>Data akan hilang selamanya dari catatan. Biasanya menghapus tidak diperlukan, cukup menolak atau mencetak.</p>
                                                            <input class="btn btn-danger" type="submit" value="Hapus"/>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <a data-toggle="modal" data-target="#hapus<?= $request->id ?>" id="detailIkon<?= $request->id ?>">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if ($numOfPages > 1): ?>
                        <ul class="pagination text-center" role="navigation" aria-label="Pagination">
                            <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                                <?php if ($i === $page): ?>
                                    <li class="current"><span class="show-for-sr">Anda di halaman</span> <?= $i ?></li>
                                <?php else: ?>
                                    <li><a href="?page=<?= $i ?>" aria-label="Halaman <?= $i ?>"><?= $i ?></a></li>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            
        </div>

        <?php $this->load->view('templates/script_foundation'); ?>
    </body>
</html>