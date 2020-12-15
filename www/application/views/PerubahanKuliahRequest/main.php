<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!doctype html>
<html class="no-js" lang="en">
<?php $this->load->view('templates/head_loggedin'); ?>

<body>
    <?php $this->load->view('templates/topbar_loggedin'); ?>
    <?php $this->load->view('templates/flashmessage'); ?>
    <br>
    <div class="container">
        <div class="card">
            <div class="card-header">
                Permohonan Baru
            </div>
            <div class="card-body">
                <p><strong>Untuk perubahan jadwal kuliah, silakan berkoordinasi langsung dengan peserta kuliah.</strong></p>
                <form method="POST" action="/PerubahanKuliahRequest/add">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>" />
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="col-form-label">Pemohon:</label>
                            <input class="form-control" type="email" name="requestByEmail" value="<?= $requestByEmail ?>" readonly />
                        </div>
                        <div class="col-lg-6">
                            <label class="col-form-label">Nama:</label>
                            <input class="form-control" type="text" name="requestByName" value="<?= $requestByName ?>" readonly="readonly" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-2">
                            <label class="col-form-label">Kode MK:</label>
                            <input class="form-control" type="text" name="mataKuliahCode" required maxlength="9" pattern="[A-Z]{3}[0-9]{3}([0-9]{3})?" title="Kode MK dalam format XYZ123" />
                        </div>
                        <div class="col-lg-5">
                            <label class="col-form-label">Nama Mata Kuliah:</label>
                            <input class="form-control" type="text" name="mataKuliahName" required />
                        </div>
                        <div class="col-lg-1">
                            <label class="col-form-label">Kelas:</label>
                            <input class="form-control" type="text" name="class" maxlength="1" />
                        </div>
                        <div class="col-lg-4">
                            <label class="col-form-label">Jenis Perubahan:</label>
                            <select name="changeType" class="form-control">
                                <?php foreach (PerubahanKuliah_model::CHANGETYPE_TYPES as $type => $name) : ?>
                                    <option value="<?= $type ?>"><?= $name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>                    
                    <div class="form-group row fromFields">
                        <div class="col-lg-3">
                            <label class="col-form-label">Dari Hari &amp; Jam:</label>
                            <input id="datetimepicker" class="form-control disableable" type="text" name="fromDateTime">
                        </div>
                        <div class="col-lg-3">
                            <label class="col-form-label">Dari Ruang:</label>
                            <input class="form-control disableable" type="text" name="fromRoom" />
                        </div>
                        <div class="col-lg-6">
                            <label class="col-form-label">Keterangan Tambahan:</label>
                            <input class="form-control disableable" type="text" name="remarks" />
                        </div>
                    </div>
                    <div class="form-group row toFields align-items-end">
                            <div class="col-lg-3">
                                <label class="col-form-label">Menjadi Hari &amp; Jam:</label>
                                <input id="datetimepicker" class="form-control disableable toDateTime" type="text" name="toDateTime[]"/>
                            </div>
                            <div class="col-lg-3">
                                <label class="col-form-label">Menjadi Ruang:</label>
                                <input class="form-control disableable toRoom" type="text" name="toRoom[]"/>
                            </div>
                            <div class="col-lg-2">
                                <label class = "col-form-label">Jam Selesai: </label>
                                <input class="form-control disableable toTimeFinish" type = "text" id="toTimeFinish" name="toTimeFinish[]" />                          
                            </div>
                            <div class="col-lg-3">
                                <br><br>
                                <a href="#" class="eraseButton btn btn-secondary">Hapus</a>
                            </div>
                    </div>
                    <div class="form-group row" id="sendDiv">
                        <div class="col-lg-12">
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
                        <?php foreach ($requests as $request) : ?>
                            <tr>
                                <th>#<?= $request->id ?></th>
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
                                    <a data-toggle="modal" data-target="#ubah<?= $request->id ?>"><i class="fas fa-pencil-alt" <?= empty($request->answer) ? '' : 'hidden' ?>></i></a>
                                    <a data-toggle="modal" data-target="#batal<?= $request->id ?>"><i class="fas fa-trash" <?= empty($request->answer) ? '' : 'hidden' ?>></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <h5></h5>

        </div>
    </div>
    <?php foreach ($requests as $request) : ?>
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
                                <?php foreach (json_decode($request->to) as $to) : ?>
                                    <tr>
                                        <th>Menjadi Hari/Jam</th>
                                        <td><time datetime="<?= $to->dateTime ?>"><?= $to->dateTime ?></time>
                                        <?= empty($to->toTimeFinish)? '': '-<time datetime="'.$to->toTimeFinish.'">
                                          '.$to->toTimeFinish.'</time>'?></td>
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
        <div class="modal fade" id="ubah<?= $request->id ?>" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Ubah Permohonan #<?= $request->id ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="/PerubahanKuliahRequest/edit">
                            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>" />
                            <input type="hidden" name="id" value="<?= $request->id ?>" />
                            <div class="form-group">
                                <label>Kode MK:</label>
                                <input class="form-control" type="text" name="editMataKuliahCode" value="<?= $request->mataKuliahCode ?>" required maxlength="9" pattern="[A-Z]{3}[0-9]{3}([0-9]{3})?" title="Kode MK dalam format XYZ123" />
                            </div>
                            <div class="form-group">
                                <label>Nama Mata Kuliah:</label>
                                <input class="form-control" type="text" name="editMataKuliahName" value="<?= $request->mataKuliahName ?>" required />
                            </div>
                            <div class="form-group">
                                <label>Keterangan Tambahan:</label>
                                <input class="form-control disableable" type="text" name="editRemarks" value="<?= $request->remarks ?>" />
                            </div>
                            <div class="form-group">
                                <label class="col-form-label">Jenis Perubahan:</label>
                                <select name="changeTypeFromEdit" class="form-control" id="<?= $request->id ?>">
                                    <?php foreach (PerubahanKuliah_model::CHANGETYPE_TYPES as $type => $name) : ?>
                                        <option value="<?= $type ?>" <?= $request->changeType === $type ? 'selected' : '' ?>><?= $name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="editable<?= $request->id ?>">
                                <?php if ($request->changeType !== 'T') : ?>
                                    <div class="form-group row">
                                        <div class=col-lg>
                                            <label>Dari Hari & jam</label>
                                            <input id="datetimepicker" class="form-control editDateTime" value="<?= strftime('%Y-%m-%d %H:%M', strtotime($request->fromDateTime)) ?>" type="text" name="editFromDateTime">
                                        </div>
                                        <div class=col-lg-3>
                                            <label>Dari Ruang</label>
                                            <input class="form-control" value="<?= $request->fromRoom ?>" type="text" name="editFromRoom">
                                        </div>
                                    </div>
                                <?php endif ?>
                                <?php foreach (json_decode($request->to) as $to) : ?>
                                    <div class="form-group row">
                                        <div class="col-lg-5">
                                            <label>Menjadi Hari & Jam:</label>
                                            <input id="datetimepicker" class="form-control editDateTime" value="<?= strftime('%Y-%m-%d %H:%M', strtotime($to->dateTime)) ?>" type="text" name="editToDateTime[]">
                                        </div>
                                        <div class="col-lg-4">
                                            <label>Menjadi Ruang:</label>
                                            <input class="form-control" value="<?= $to->room ?>" type="text" name="editToRoom[]">
                                        </div>
                                        <div class="col-lg-3">
                                            <label>Jam Selesai:</label>
                                            <input class="form-control editTimeFinish" value="<?= strftime('%H:%M', strtotime($to->toTimeFinish)) ?>" type="text" name="editToFinishTime[]">
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <p>&nbsp;</p>
                            <div class="form-group footer">
                                <input type="submit" class="btn btn-success" value="Ubah" <?= empty($request->answer) ? '' : 'disabled' ?> />
                                <a href="#" id="editAddToButton" class="btn btn-secondary <?= ($request->changeType === 'X')? 'disabled':'' ?>">Tambah Pertemuan Ekstra</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="batal<?= $request->id ?>" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Batalkan Permohonan #<?= $request->id ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="/PerubahanKuliahRequest/remove">
                            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>" />
                            <input type="hidden" name="id" value="<?= $request->id ?>" />
                            <input type="hidden" name="answer" value="remove" />
                            <p><strong>Yakin ingin membatalkan?</strong></p>
                            <p>Jika ingin merubah keperluan dapat klik tombol edit. Data yang telah dihapus tidak dapat dipulihkan.</p>
                            <input type="submit" class="btn btn-danger" value="Hapus" <?= empty($request->answer) ? '' : 'disabled' ?> />

                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <?php $this->load->view('templates/script_foundation'); ?>
    <script>
        $(document).ready(function() {
            var datepickeroptions = {
                format: 'Y-m-d H:i',
                minTime: '07:00',
                maxTime: '18:00'
            };
            var timefinishpicker = {
                datepicker: false,
                format: 'H:i',
                minTime: '07:00',
                maxTime: '18:00'
            };

            function removeRow() {
                $(this).closest('.row').remove();
                //return false;
            }
            jQuery('#datetimepicker').datetimepicker();
            jQuery('#toTimeFinish').datetimepicker(timefinishpicker);
            $('#fromDateTime').datetimepicker(datepickeroptions);
            $('.editDateTime').datetimepicker(datepickeroptions);
            $('.editTimeFinish').datetimepicker(timefinishpicker);
            $('.toDateTime').datetimepicker(datepickeroptions);
            $('.eraseButton').click(removeRow);
            $('select[name="changeType"]').change(function() {
                $('input.disableable').removeAttr('disabled');
                switch ($(this).val()) {
                    case 'T':
                        $('input[name="fromDateTime"]').attr('disabled', 'disabled');
                        $('input[name="fromRoom"]').attr('disabled', 'disabled');
                        break;
                    case 'X':
                        $('input.toDateTime').attr('disabled', 'disabled');
                        $('input.toRoom').attr('disabled', 'disabled');
                        $('input.toTimeFinish').attr('disabled', 'disabled');
                        break;
                }
            });
            $('select[name="changeType"]').change();

            $('select[name="changeTypeFromEdit"]').change(function() {
                var id = $(this).attr('id');
                // workingField is the editable html part 
                var workingField = $('.editable' + id);
                workingField.removeClass();
                workingField.empty();
                workingField.nextAll('.footer').find('#editAddToButton').removeClass('disabled');
                switch ($(this).val()) {
                    case 'T':
                        workingField.addClass("form-group editable" + id);
                        workingField.append("<div class ='form-group row'></div>");
                        var fromChild = workingField.children().eq(0);
                        fromChild.append($('.toFields').clone().children().removeClass());
                        fromChild.children().eq(0).addClass("col-lg-5");
                        fromChild.children().eq(1).addClass("col-lg-4");
                        fromChild.children().eq(2).addClass("col-lg-3");
                        fromChild.find('.toDateTime').datetimepicker(datepickeroptions)
                            .attr('name', 'editToDateTime[]').removeClass('disableable').addClass('editDisableable');
                        fromChild.find('.toTimeFinish').datetimepicker(timefinishpicker)
                            .attr('name', 'editToFinishTime[]').removeClass('disableable').addClass('editDisableable');
                        fromChild.find('.toRoom').attr('name', 'editToRoom[]')
                            .removeClass('disableable').addClass('editDisableable');
                        $('input.editDisableable').removeAttr('disabled');
                        break;
                    case 'X':
                        workingField.addClass("editable" + id);
                        workingField.append("<div class ='form-group row'></div>");
                        var fromChild = workingField.children().eq(0);
                        fromChild.append($('.fromFields').clone().children().removeClass())
                        fromChild.children().eq(0).addClass("col-lg");
                        fromChild.children().eq(1).addClass("col-lg-3");
                        fromChild.find('#datetimepicker').datetimepicker(datepickeroptions);
                        fromChild.find("input[name=fromDateTime]").attr('name', 'editFromDateTime')
                            .addClass('editDisableable').removeClass('disableable');
                        fromChild.find("input[name=fromRoom]").attr('name', 'editFromRoom')
                            .addClass('editDisableable').removeClass('disableable');
                        fromChild.children().eq(2).remove();
                        $('input.editDisableable').removeAttr('disabled');
                        workingField.nextAll('.footer').find('#editAddToButton').addClass('disabled');
                        break;
                    case 'G':
                        workingField.addClass("editable" + id);
                        workingField.append("<div class ='form-group row'></div>");
                        var fromChild = workingField.children().eq(0);
                        // fromChild is element group that controls fromDateTime,fromRoom
                        fromChild.append($('.fromFields').clone().children().removeClass());
                        fromChild.children().eq(0).addClass("col-lg");
                        fromChild.children().eq(1).addClass("col-lg-3");
                        fromChild.find("input[name=fromDateTime]").attr('name', 'editFromDateTime')
                            .addClass('editDisableable').removeClass('disableable');
                        fromChild.find("input[name=fromRoom]").attr('name', 'editFromRoom')
                            .addClass('editDisableable').removeClass('disableable');

                        fromChild.children().eq(2).remove();

                        workingField.append("<div class ='form-group row'></div>");
                        var lastChild = workingField.children().last();
                        //lastChild is the last children of the element
                        lastChild.append($('.toFields').clone().children().removeClass());
                        lastChild.children().eq(0).addClass("col-lg-5");
                        lastChild.children().eq(1).addClass("col-lg-4");
                        lastChild.children().eq(2).addClass("col-lg-3");
                        lastChild.find('.toDateTime').attr('name', 'editToDateTime[]')
                            .removeClass('disableable').addClass('editDisableable');
                        lastChild.find('.toTimeFinish').datetimepicker(timefinishpicker)
                            .attr('name', 'editToFinishTime[]').removeClass('disableable').addClass('editDisableable');
                        lastChild.find('.toRoom').attr('name', 'editToRoom[]')
                            .removeClass('disableable').addClass('editDisableable');
                        workingField.find('#datetimepicker').datetimepicker(datepickeroptions);

                        $('input.editDisableable').removeAttr('disabled');
                        break;
                }
            });

            var toFields = $('.toFields').clone();
            $('.toFields').find('.eraseButton').remove();
            $('#addToButton').click(function(e) {
                e.preventDefault();
                var newFields = toFields.clone();
                newFields.insertBefore($('#sendDiv'));
                newFields.find('.toDateTime').datetimepicker(datepickeroptions);
                newFields.find('.toTimeFinish').datetimepicker(timefinishpicker);
                newFields.find('.eraseButton').click(removeRow);
            });

            $('.footer').on('click', '#editAddToButton', function(e) {
                e.preventDefault();
                var editNewFields = toFields.clone();
                editNewFields.children().eq(0).removeClass().addClass("col-lg-5");
                editNewFields.children().eq(1).removeClass().addClass("col-lg-4");
                editNewFields.children().eq(2).removeClass().addClass("col-lg-3");
                editNewFields.children().eq(3).removeClass().addClass("col-lg")
                    .find('.eraseButton').addClass('form-control').click(removeRow);
                editNewFields.find('.toDateTime').datetimepicker(datepickeroptions).attr('name', 'editToDateTime[]')
                    .removeClass('toDateTime').addClass('editDateTime');
                editNewFields.find('.toTimeFinish').datetimepicker(timefinishpicker).attr('name', 'editToFinishTime[]')
                    .removeClass('toTimeFinish').addClass('editTimeFinish');
                editNewFields.find('.toRoom').attr('name', 'editToRoom[]').removeClass('toRoom');
                var workingField = $(this).parents('.form-group').prevAll().eq(1);
                workingField.append(editNewFields);
            });
        

        });
    </script>
</body>

</html>