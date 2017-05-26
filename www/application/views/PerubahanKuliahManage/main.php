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
                <h5>Permohonan Perubahan Kuliah</h5>
                <table class="stack">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Status</th>
                            <th>Tanggal Permohonan</th>
                            <th>Kode MK</th>
                            <th>Perubahan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($requests as $request): ?>
                            <tr>
                                <td>#<?= $request->id ?></td>
                                <td><span class="<?= $request->labelClass ?> label"><?= $request->status ?></span></td>
                                <td><time datetime="<?= $request->requestDateTime ?>"><?= $request->requestDateString ?></time></td>
                                <td><?= $request->mataKuliahCode ?></td>
                                <td><?= PerubahanKuliah_model::CHANGETYPE_TYPES[$request->changeType] ?></td>
                                <td>
                                    <a data-open="detail<?= $request->id ?>"><i class="fi-eye"></i></a>
                                    <a target="_blank" href="/PerubahanKuliahManage/printview/<?= $request->id ?>"><i class="fi-print"></i></a>
                                    <a data-open="konfirmasi<?= $request->id ?>"><i class="fi-like"></i></a>                                    
                                    <a data-open="tolak<?= $request->id ?>"><i class="fi-dislike"></i></a>
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
        <?php foreach ($requests as $request): ?>

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
                <button class="close-button" data-close aria-label="Tutup" type="button">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="reveal" id="konfirmasi<?= $request->id ?>" data-reveal>
                <h5>Konfirmasi Permohonan</h5>
                <form method="POST" action="/PerubahanKuliahManage/answer">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>" />
                    <input type="hidden" name="id" value="<?= $request->id ?>"/>
                    <input type="hidden" name="answer" value="confirmed"/>
                    <label>Email penjawab:
                        <input type="text" value="<?= $answeredByEmail ?>" readonly="true"/>
                    </label>
                    <label>Keterangan:
                        <input name="answeredMessage" class="input-group-field" type="text"/>
                    </label>
                    <p>&nbsp;</p>                                            
                    <input type="submit" class="success button" value="Konfirmasi"/>
                </form>
                <button class="close-button" data-close aria-label="Tutup" type="button">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>        
            <div class="reveal" id="tolak<?= $request->id ?>" data-reveal>
                <h5>Tolak Permohonan</h5>
                <form method="POST" action="/PerubahanKuliahManage/answer">
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
            <div class="reveal" id="hapus<?= $request->id ?>" data-reveal>
                <h5>Hapus Permohonan</h5>
                <form method="POST" action="/PerubahanKuliahManage/remove">
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
        <?php endforeach; ?>        
        <?php $this->load->view('templates/script_foundation'); ?>
    </body>
</html>