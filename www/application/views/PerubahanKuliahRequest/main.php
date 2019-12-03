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
                    Permohonan Baru
                </div>
                <div class="card-body">
                    <form class="p-3" method="POST" action="/PerubahanKuliahRequest/add">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>" />
                        <div class="form-group row">
                            <div class="col-sm-6">
                                <label class="col-form-label">Pemohon:</label>
                                <input class="form-control" type="email" name="requestByEmail" value="<?= $requestByEmail ?>" readonly/>
                            </div>
                            <div class="col-sm-6">
                                <label class="col-form-label">Nama:</label>
                                <input class="form-control" type="text" name="requestByName" value="<?= $requestByName ?>" readonly="readonly"/>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-2">
                                <label class="col-form-label">Kode MK:</label>
                                <input class="form-control" type="text" name="mataKuliahCode" required maxlength="9" pattern="[A-Z]{3}[0-9]{3}([0-9]{3})?" title="Kode MK dalam format XYZ123"/>
                            </div>
                            <div class="col-sm-5">
                                <label class="col-form-label">Nama Mata Kuliah:</label>
                                <input class="form-control" type="text" name="mataKuliahName" required/>
                            </div>
                            <div class="col-sm-1">
                                <label class="col-form-label">Kelas:</label>
                                <input class="form-control" type="text" name="class" maxlength="1"/>
                            </div>
                            <div class="col-sm-4">
                                <label class="col-form-label">Jenis Perubahan:</label>
                                <select name="changeType" class="form-control">
                                    <?php foreach (PerubahanKuliah_model::CHANGETYPE_TYPES as $type => $name): ?>
                                        <option value="<?= $type ?>"><?= $name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label class="col-form-label">Dari Hari &amp; Jam:</label>
                                <input id="datetimepicker" class="form-control disableable" type="text" name="fromDateTime">
                            </div>
                            <div class="col-sm-3">
                                <label class="col-form-label">Dari Ruang:</label>
                                <input class="form-control disableable" type="text" name="fromRoom"/>
                            </div>
                            <div class="col-sm-6">
                                <label class="col-form-label">Keterangan Tambahan:</label>
                                <input class="form-control disableable" type="text" name="remarks"/>
                            </div>
                        </div>
                        <div class="form-group row toFields">
                            <div class="col-sm-3">
                                <label class="col-form-label">Menjadi Hari &amp; Jam:</label>
                                <input id="datetimepicker" class="form-control disableable toDateTime" type="text" name="toDateTime[]"/>
                            </div>
                            <div class="col-sm-3">
                                <label class="col-form-label">Menjadi Ruang:</label>
                                <input class="form-control disableable toRoom" type="text" name="toRoom[]"/>
                            </div>
                            <div class="col-sm-2">
                                <br/><br>
                                <a href="#" class="eraseButton btn btn-secondary">Hapus</a>
                            </div>
                        </div>
                        <div class="form-group row" id="sendDiv">
                            <div class="col-sm-12">
                                <input type="submit" class="btn btn-primary" value="Kirim Permohonan">
                                <a href="#" id="addToButton" class="btn btn-secondary">Tambah Pertemuan Ekstra</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <br>
            <div class="card">
                <div class="card-header">
                    Histori Permohonan
                </div>
                <div class="card-body">
                    <table class="table table-striped table-responsive">
                        <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Status</th>
                            <th scope="col">Tanggal Permohonan</th>
                            <th scope="col">Kode MK</th>
                            <th scope="col">Perubahan</th>
                            <th scope="col">Tanggal Jawab</th>
                            <th scope="col">Keterangan</th>
                            <th scope="col">Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($requests as $request): ?>
                            <tr>
                                <td>#<?= $request->id ?></td>
                                <td><span class=" badge badge-<?= $request->labelClass ?>"><?= $request->status ?></span></td>
                                <td><time datetime="<?= $request->requestDateTime ?>"><?= $request->requestDateString ?></time></td>
                                <td><?= $request->mataKuliahCode ?></td>
                                <td><?= PerubahanKuliah_model::CHANGETYPE_TYPES[$request->changeType] ?></td>
                                <td><time datetime="<?= $request->answeredDateTime ?>"><?= $request->answeredDateString ?></time></td>
                                <td><?= $request->answeredMessage ?></td>
                                <td>
                                    <a data-toggle="modal" data-target="#detail<?= $request->id ?>" id="detailIkon<?= $request->id ?>">
                                    <span style="font-size: 18px; color: Dodgerblue;">
                                      <i class="fas fa-eye"></i>
                                    </span>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <h5></h5>

            </div>
        </div>
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
                jQuery('#datetimepicker').datetimepicker();
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