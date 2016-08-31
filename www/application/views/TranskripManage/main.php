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
                <form method="GET" action="/TranskripManage">
                    <div class="input-group">
                        <span class="input-group-label">Cari NPM:</span>
                        <input name="npm" class="input-group-field" type="text" placeholder="2013730013" maxlength="10" minlength="10"<?= $npmQuery === NULL ? '' : " value='$npmQuery'" ?>/>
                        <div class="input-group-button">
                            <input class="button" type="submit" value="Cari"/>
                        </div>
                    </div>
                </form>
                <table class="stack">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Status</th>
                            <th>Tanggal Permohonan</th>
                            <th>Tipe Transkrip</th>
                            <th>NPM</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($requests as $request): ?>
                            <tr>
                                <td>#<?= $request->id ?></td>
                                <td><span class="<?= $request->labelClass ?> label"><?= $request->status ?></span></td>
                                <td><time datetime="<?= $request->requestDateTime ?>"><?= $request->requestDateString ?></time></td>
                                <td><?= $request->requestType ?></td>
                                <td><?= isset($request->requestByNPM) ? $request->requestByNPM : '-' ?></td>
                                <td>
                                    <div class="reveal" id="detail<?= $request->id ?>" data-reveal>
                                        <h5>Detail Permohonan #<?= $request->id ?></h5>
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
                                        <button class="close-button" data-close aria-label="Tutup" type="button">
                                            <span aria-hidden="true">&times;</span>
                                        </button>                                        
                                    </div>
                                    <a data-open="detail<?= $request->id ?>"><i class="fi-eye"></i></a>
                                    <div class="reveal" id="tolak<?= $request->id ?>" data-reveal>
                                        <h5>Tolak Permohonan</h5>
                                        <form method="POST" action="/TranskripManage/answer">
                                            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>" />
                                            <input type="hidden" name="id" value="<?= $request->id ?>"/>
                                            <input type="hidden" name="answer" value="rejected"/>
                                            <label>Email penjawab:
                                                <input type="text" value="<?= $answeredByEmail ?>" readonly="true"/>
                                            </label>
                                            <label>Alasan penolakan:
                                                <input name="answeredMessage" class="input-group-field" type="text" required/>
                                            </label>
                                            <p>&nbsp;</p>                                            
                                            <input type="submit" class="alert button" value="Tolak"/>
                                        </form>
                                        <button class="close-button" data-close aria-label="Tutup" type="button">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <a data-open="tolak<?= $request->id ?>"><i class="fi-dislike"></i></a>
                                    <div class="reveal" id="cetak<?= $request->id ?>" data-reveal>
                                        <h5>Cetak Permohonan</h5>
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
                                            <label>Email penjawab:
                                                <input type="text" value="<?= $answeredByEmail ?>" readonly="true"/>
                                            </label>
                                            <label>Keterangan tambahan:
                                                <input name="answeredMessage" class="input-group-field" type="text" required/>
                                            </label>
                                            <p>&nbsp;</p>                                            
                                            <input type="submit" class="button" value="Sudah dicetak"/>
                                        </form>
                                        <button class="close-button" data-close aria-label="Tutup" type="button">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <a data-open="cetak<?= $request->id ?>"><i class="fi-print"></i></a>
                                    <div class="reveal" id="hapus<?= $request->id ?>" data-reveal>
                                        <h5>Hapus Permohonan</h5>
                                        <form method="POST" action="/TranskripManage/remove">
                                            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>" />
                                            <input type="hidden" name="id" value="<?= $request->id ?>"/>
                                            <input type="hidden" name="answer" value="remove"/>
                                            <p><strong>Yakin ingin menghapus?</strong></p>
                                            <p>Data akan hilang selamanya dari catatan. Biasanya menghapus tidak diperlukan, cukup menolak atau mencetak.</p>
                                            <input type="submit" class="alert button" value="Hapus"/>
                                        </form>
                                        <button class="close-button" data-close aria-label="Tutup" type="button">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <a data-open="hapus<?= $request->id ?>"><i class="fi-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
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