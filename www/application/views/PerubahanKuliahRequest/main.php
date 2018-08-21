<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!doctype html>
<html class="no-js" lang="en">
    <?php $this->load->view('templates/head_loggedin'); ?>
    <body>
        <?php $this->load->view('templates/topbar_loggedin'); ?>
        <?php $this->load->view('templates/flashmessage'); ?>

        <div class="row">
            <div class="large-12 column">
                <div class="callout">
                    <h5>Permohonan Baru</h5>
                    <form method="POST" action="/PerubahanKuliahRequest/add">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>" />
                        <div class="row">
                            <div class="large-4 column">
                                <label>Pemohon:
                                    <input type="email" name="requestByEmail" value="<?= $requestByEmail ?>" readonly="readonly"/>
                                </label>
                            </div>
                            <div class="large-8 column">
                                <label>Nama:
                                    <input type="text" name="requestByName" value="<?= $requestByName ?>" readonly="readonly"/>
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="large-2 column">
                                <label>Kode MK:
                                    <input type="text" name="mataKuliahCode" required maxlength="9" pattern="[A-Z]{3}[0-9]{3}([0-9]{3})?" title="Kode MK dalam format XYZ123"/>
                                </label>
                            </div>
                            <div class="large-5 column">
                                <label>Nama Mata Kuliah:
                                    <input type="text" name="mataKuliahName" required/>
                                </label>
                            </div>
                            <div class="large-1 column">
                                <label>Kelas:
                                    <input type="text" name="class" maxlength="1"/>
                                </label>
                            </div>
                            <div class="large-4 column">
                                <label>Jenis Perubahan:
                                    <select name="changeType">
                                        <?php foreach (PerubahanKuliah_model::CHANGETYPE_TYPES as $type => $name): ?>
                                            <option value="<?= $type ?>"><?= $name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </label>
                            </div>                            
                        </div>
                        <div class="row">
                            <div class="large-3 column">
                                <label>Dari Hari &amp; Jam:
                                    <input class="disableable" type="text" name="fromDateTime" id="fromDateTime"/>
                                </label>
                            </div>
                            <div class="large-3 column">
                                <label>Dari Ruang:
                                    <input class="disableable" type="text" name="fromRoom"/>
                                </label>
                            </div>
                            <div class="large-6 column">
                                <label>Keterangan Tambahan:
                                    <input class="disableable" type="text" name="remarks"/>
                                </label>
                            </div>                                                        
                        </div>
                        <div class="row toFields">
                            <div class="large-3 column">
                                <label>Menjadi Hari &amp; Jam:
                                    <input class="disableable toDateTime" type="text" name="toDateTime[]"/>
                                </label>
                            </div>
                            <div class="large-3 column">
                                <label>Menjadi Ruang:
                                    <input class="disableable toRoom" type="text" name="toRoom[]"/>
                                </label>
                            </div>
                            <div class="large-6 column">
                                <br/>
                                <a href="#" class="eraseButton button secondary">Hapus</a>
                            </div>
                        </div>
                        <div class="row" id="sendDiv">
                            <div class="large-12 column">
                                <input type="submit" class="button" value="Kirim Permohonan">
                                <a href="#" id="addToButton" class="button secondary">Tambah Pertemuan Ekstra</a>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="callout">
                    <h5>Histori Permohonan</h5>
                    <table class="stack">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Status</th>
                                <th>Tanggal Permohonan</th>
                                <th>Kode MK</th>
                                <th>Perubahan</th>
                                <th>Tanggal Jawab</th>
                                <th>Keterangan</th>
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
                                    <td><time datetime="<?= $request->answeredDateTime ?>"><?= $request->answeredDateString ?></time></td>
                                    <td><?= $request->answeredMessage ?></td>
                                    <td>
                                        <a data-open="detail<?= $request->id ?>"><i class="fi-eye"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
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
        <?php endforeach; ?>
        <?php $this->load->view('templates/script_foundation'); ?>
        <script>
            $(document).ready(function () {
                var datepickeroptions = {
                    format: 'Y-m-d H:i'
                };
                function removeRow() {
                    $(this).closest('.row').remove();
                }
                $('#fromDateTime').datetimepicker(datepickeroptions);
                $('.toDateTime').datetimepicker(datepickeroptions);
                $('.eraseButton').click(removeRow);
                $('select[name="changeType"]').change(function () {
                    $('input.disableable').removeAttr('disabled');
                    switch ($(this).val()) {
                        case 'T':
                            $('input[name="fromDateTime"]').attr('disabled', 'disabled');
                            $('input[name="fromRoom"]').attr('disabled', 'disabled');
                            break;
                        case 'X':
                            $('input.toDateTime').attr('disabled', 'disabled');
                            $('input.toRoom').attr('disabled', 'disabled');
                            break;
                    }
                });
                $('select[name="changeType"]').change();
                var toFields = $('.toFields').clone();
                $('.toFields').find('.eraseButton').remove();
                $('#addToButton').click(function(e) {
                    e.preventDefault();
                    var newFields = toFields.clone();
                    newFields.insertBefore($('#sendDiv'));
                    newFields.find('.toDateTime').datetimepicker(datepickeroptions);
                    newFields.find('.eraseButton').click(removeRow);
                });
            });
        </script>
    </body>
</html>