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
                    Permohonan Perubahan Kuliah
                </div>
                <br>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Status</th>
                            <th scope="col">Tanggal Permohonan</th>
                            <th scope="col">Kode MK</th>
                            <th scope="col">Perubahan</th>
                            <th scope="col">Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($requests as $request): ?>
                            <tr>
                                <td>#<?= $request->id ?></td>
                                <td><span class="badge badge-<?= $request->labelClass ?>"><?= $request->status ?></span></td>
                                <td><time datetime="<?= $request->requestDateTime ?>"><?= $request->requestDateString ?></time></td>
                                <td><?= $request->mataKuliahCode ?></td>
                                <td><?= PerubahanKuliah_model::CHANGETYPE_TYPES[$request->changeType] ?></td>
                                <td>
                                    <a data-toggle="modal" data-target="#detail<?= $request->id ?>" id="detailIkon<?= $request->id ?>"><i class="fas fa-eye blueiconcolor"></i></a>
                                    <a target="_blank" href="/PerubahanKuliahManage/printview/<?= $request->id ?>"><i class="fas fa-print"></i></a>
                                    <a data-toggle="modal" data-target="#konfirmasi<?= $request->id ?>"><i class="fas fa-thumbs-up"></i></a>
                                    <a data-toggle="modal" data-target="#tolak<?= $request->id ?>"><i class="fas fa-thumbs-down"></i></a>
                                    <a data-toggle="modal" data-target="#hapus<?= $request->id ?>"><i class="fas fa-trash"></i></a>
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
                <?php foreach ($requests as $request): ?>
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
                                            <th>Kode Mata Kuliah</th>
                                            <td><?= $request->mataKuliahCode ?></td>
                                        </tr>
                                        <tr>
                                            <th>Nama Mata Kuliah</th>
                                            <td><?= $request->mataKuliahName ?></td>
                                        </tr>
                                        <tr>
                                            <th>Kelas</th>
                                            <td><?= $request->class ?></td>
                                        </tr>
                                        <tr>
                                            <th>Jenis Perubahan</th>
                                            <td><?= PerubahanKuliah_model::CHANGETYPE_TYPES[$request->changeType] ?></td>
                                        </tr>
                                        <tr>
                                            <th>Dari Hari/Jam</th>
                                            <td><time datetime="<?= $request->fromDateTime ?>"><?= $request->fromDateTime ?></time></td>
                                        </tr>
                                        <tr>
                                            <th>Dari Ruang</th>
                                            <td><?= $request->fromRoom ?></td>
                                        </tr>
                                        <?php foreach (json_decode($request->to) as $to): ?>
                                            <tr>
                                                <th>Menjadi Hari/Jam</th>
                                                <td><time datetime="<?= $to->dateTime ?>"><?= $to->dateTime ?></time></td>
                                            </tr>
                                            <tr>
                                                <th>Menjadi Ruang</th>
                                                <td><?= $to->room ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <tr>
                                            <th>Keterangan</th>
                                            <td><?= $request->remarks ?></td>
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
                    <div class="modal fade" id="konfirmasi<?= $request->id ?>" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">Konfirmasi Permohonan #<?= $request->id ?></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="/PerubahanKuliahManage/answer">
                                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>" />
                                        <input type="hidden" name="id" value="<?= $request->id ?>"/>
                                        <input type="hidden" name="answer" value="confirmed"/>
                                        <div class="form-group">
                                            <label>Email penjawab:</label>
                                            <input class="form-control" type="text" value="<?= $answeredByEmail ?>" readonly="true"/>
                                        </div>
                                        <div class="form-group">
                                            <label>Keterangan:</label>
                                            <input class="form-control" name="answeredMessage" class="input-group-field" type="text"/>
                                        </div>
                                        <p>&nbsp;</p>
                                        <div class="form-group">
                                            <input type="submit" class="btn btn-success" value="Konfirmasi"/>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
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
                                    <form method="POST" action="/PerubahanKuliahManage/answer">
                                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>" />
                                        <input type="hidden" name="id" value="<?= $request->id ?>"/>
                                        <input type="hidden" name="answer" value="rejected"/>
                                        <div class="form-group">
                                            <label>Email penjawab:</label>
                                            <input class="form-control" type="text" value="<?= $answeredByEmail ?>" readonly="true"/>
                                        </div>
                                        <div class="form-group">
                                            <label>Alasan penolakan:</label>
                                            <input class="form-control" name="answeredMessage" class="input-group-field" type="text" required/>
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
                                    <form method="POST" action="/PerubahanKuliahManage/remove">
                                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>" />
                                        <input type="hidden" name="id" value="<?= $request->id ?>"/>
                                        <input type="hidden" name="answer" value="remove"/>
                                        <p><strong>Yakin ingin menghapus?</strong></p>
                                        <p>Data akan hilang selamanya dari catatan. Biasanya menghapus tidak diperlukan, cukup menolak atau mencetak.</p>
                                        <input type="submit" class="btn btn-danger" value="Hapus"/>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php $this->load->view('templates/script_foundation'); ?>
            </div>
        </div>
    </body>
</html>