<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!doctype html>
<html class="no-js" lang="en">
    <?php $this->load->view('templates/script_foundation'); ?>
    <?php $this->load->view('templates/head_loggedin'); ?>
    <?php $this->load->helper('url'); ?>
    <body>
        <?php $this->load->view('templates/topbar_loggedin'); ?>

        <div class="row">

            <div class="large-12 column callout">
                <h5>Tambah Jadwal</h5>
                <div class="large-4 columns">
                    <form method="POST" action="/EntriJadwalDosen/add">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>" />
                        Hari
                        <select name="hari"> 
                            <?php
                            $hariValue = 0;
                            foreach ($namaHari as $hari) {
                                ?>
                                <option value="<?= $hariValue ?>"> <?= $hari ?> </option>

                                <?php
                                $hariValue++;
                            }
                            ?>
                        </select><br>
                        Jam Mulai
                        <select name="jam_mulai"> 
                            <?php for ($i = 7; $i <= 16; $i++) { ?>
                                <option value="<?php echo $i ?>"> <?php echo $i ?>:00 </option>
                            <?php } ?>
                        </select><br>
                        </div>
                        <div class=" large-4 columns">
                            Durasi
                            <select name="durasi"> 
                                <?php for ($i = 1; $i <= 9; $i++) { ?>
                                    <option value="<?php echo $i ?>"> <?php echo $i ?> jam </option>
                                <?php } ?>
                            </select><br>
                            Jenis  
                            <select name="jenis_jadwal"> 
                                <option value="konsultasi" style="background-color:yellow"> Konsultasi </option>
                                <option value="terjadwal" style="background-color:green;color:white"> Terjadwal</option>
                                <option value="kelas" style="background-color:white"> Kelas </option>
                            </select>
                        </div>
                        <div class="large-4 columns">
                            Label <input type="text" name="label_jadwal"><br>
                            <input type="submit" class="button" value="Tambah">
                            </form>
                        </div>
                </div>
                <!-- ===================================================================== Pembentukan Tabel ============================================================================= -->

                <div class="large-12 column callout">
                    <h5>Daftar Jadwal</h5>
                    <div class="table-scroll" id="jadwal_table">
                        <table border=1>
                            <tr> 
                                <td style='width:10%'></td>
                                <?php
                                for ($i = 0; $i < 5; $i++) {
                                    echo "<td style='width:18%'> $namaHari[$i] </td>"; //Membuat Header Tabel yang berisi daftar hari
                                }
                                ?>
                            </tr>
                            <?php
                            //GENERATE BODY UTAMA TABEL
                            $cellRowID = 1;
                            for ($i = 7; $i < 17; $i++) {
                                echo "<tr><td>" . $i . "-" . ($i + 1);
                                $cellColID = 1;
                                for ($j = 0; $j < 5; $j++) {
                                    echo"<td align='center' id='cell" . $cellRowID . "-" . $cellColID . "'>" . "</td>";
                                    $cellColID++;
                                }
                                $cellRowID++;
                            }
                            // MEWARNAI TABEL
                            $rowIdx = 1;
                            $spanCounter = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
                            $cellRowID = 0;
                            foreach ($dataJadwal as $dataHariIni) {
                                $colIdx = $dataHariIni->hari + 1;   // + 1 karena perbedaan selisih index tabel dan value hari di database 
                                $rowIdx = $dataHariIni->jam_mulai - 6;  // + 1 karena perbedaan selisih index tabel dan value jam_mulai di database 
                                if ($dataHariIni->jenis == "konsultasi") {
                                    $color = "#FEFF00";
                                } else if ($dataHariIni->jenis == "kelas") {
                                    $color = "#FFFFFF";
                                } else {
                                    $color = "#92D14F";
                                }
                                ?>
                                <script type="text/javascript">
                                    var table = document.getElementById('jadwal_table');
                                    var rows = table.getElementsByTagName('tr');
                                    var $cellLocation = "#cell<?php echo $rowIdx; ?>-<?php echo $colIdx; ?>";

                                    $($cellLocation).css('background-color', '<?php echo $color; ?>');
                                    $($cellLocation).attr('rowspan', <?php echo $dataHariIni->durasi ?>);

                                    //menghapus cell-cell yang tergeser karena rowspan
                                    for (i = <?php echo ($rowIdx + 1); ?>; i < <?php echo ($rowIdx + $dataHariIni->durasi); ?>; i++) {
                                        $("#cell" + i + "-" +<?php echo $colIdx; ?>).remove();
                                    }
                                    $($cellLocation).html("<?php echo $dataHariIni->label ?>");

                                    //membuat cell-cell yang telah diwarnai jadi memunculkan pop-up untuk mengedit jadwal ketika diklik oleh mouse
                                    $(document).on("click", $cellLocation, function () {
                                        var $menuName = "#edit_menu<?php echo $dataHariIni->id ?>";
                                        $($menuName).foundation('open');
                                    });
                                </script>
                                <?php
                            }
                            $cellRowID++;
                            $rowIdx++;
                            ?>
                        </table>
                    </div>
					<?php
					if($dataJadwal!=null){
						$palingBaru=$dataJadwal[0]->lastUpdate;
						foreach($dataJadwal as $perJadwal){
							if($palingBaru<$perJadwal->lastUpdate){
									$palingBaru=$perJadwal->lastUpdate;
							}
						}
					$tgl = date('d',strtotime($palingBaru));
					$tgl = $tgl.' '.$namaBulan[date('m',strtotime($palingBaru)) - 1]; // minus 1 karena index dimulai dari 0
					$tgl = $tgl.' '.date('Y',strtotime($palingBaru));
					?>
						Terakhir diupdate pada : <?=$tgl?><br>
					<?php
					}
					else{
						$palingBaru=FALSE;
						?>
						Terakhir diupdate pada : Belum ada jadwal <br>
					<?php
					}
					?>
					<a href="/EntriJadwalDosen/deleteAll/export/" class="alert button" onClick="return konfirmasi();">Delete All</a>
					<script>
						function konfirmasi()
						{
							yakin = confirm("Anda yakin mau menghapus semua data jadwal? Aksi ini tidak dapat dibatalkan");
							if(yakin!=true)
							{
								return false;
							}
						}
					</script>
				</div>
				
 <!-- ===================================================================== END Pembentukan Tabel ============================================================================= -->
               
<!--  ===================================================================== MENU EDIT JADWAL =================================================================================== -->
			   <?php foreach ($dataJadwal as $dataHariIni) { ?>
                    <div id="edit_menu<?php echo $dataHariIni->id ?>" class="reveal"  data-reveal >
                        <button class="close-button" data-close aria-label="Close modal" type="button">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h5> Edit Jadwal </h5>
                        <form name="form<?php echo $dataHariIni->id ?>" method="POST" action="/EntriJadwalDosen/update/<?php echo $dataHariIni->id ?>">
                            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>" />
                            <input type="hidden" name="id_jadwal_parameter" value="<?php echo $dataHariIni->id ?>"> </a> <br>
                            Hari 
                            <select name="hari"> 
                                <?php
                                $hariValue = 0;
                                foreach ($namaHari as $hari) {
                                    if ($hariValue == $dataHariIni->hari) {
                                        ?>
                                        <option value="<?= $hariValue ?>" selected> <?= $hari ?> </option>
                                        <?php
                                    } else {
                                        ?>
                                        <option value="<?= $hariValue ?>"> <?= $hari ?> </option>
                                        <?php
                                    }
                                    $hariValue++;
                                }
                                ?>
                            </select><br>
                            Jam Mulai
                            <select name="jam_mulai"> 
                                <?php
                                for ($i = 7; $i <= 16; $i++) {
                                    if ($i == $dataHariIni->jam_mulai) {
                                        ?>
                                        <option value="<?php echo $i ?>" selected> <?php echo $i ?>:00 </option>
                                        <?php
                                    } else {
                                        ?>
                                        <option value="<?php echo $i ?>"> <?php echo $i ?>:00 </option>
                                        <?php
                                    }
                                }
                                ?>
                            </select><br>
                            Durasi
                            <select name="durasi"> 
                                <?php
                                for ($i = 1; $i <= 9; $i++) {
                                    if ($i == $dataHariIni->durasi) {
                                        ?>
                                        <option value="<?php echo $i ?>" selected> <?php echo $i ?> jam </option>
                                        <?php
                                    } else {
                                        ?>
                                        <option value="<?php echo $i ?>"> <?php echo $i ?> jam </option>
                                        <?php
                                    }
                                }
                                ?>
                            </select><br>
                            Jenis  
                            <select name="jenis_jadwal"> 
                                <option style="background-color:yellow" value="konsultasi" <?php if ($dataHariIni->jenis == 'konsultasi') echo "selected"; ?> > Konsultasi </option>
                                <option style="background-color:green" value="terjadwal" <?php if ($dataHariIni->jenis == 'terjadwal') echo "selected"; ?>> Terjadwal</option>
                                <option style="background-color:white" value="kelas" <?php if ($dataHariIni->jenis == 'kelas') echo "selected"; ?>> Kelas </option>
                            </select>
                            Label <input type="text" name="label_jadwal" value="<?php echo $dataHariIni->label; ?>"><br> 
                           <div class="row large-4 column">
                            <div class="large-2 column">
                                <input type="submit" name="submitId<?php echo $dataHariIni->id ?>" class="button" value="Save  ">
                                </form>
                            </div>
                            <div class="large-2 column ">
                                <form name="formDelete<?php echo $dataHariIni->id ?>" method="POST" action="/EntriJadwalDosen/delete/<?php echo $dataHariIni->id ?>">
                                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>" />
                                    <input type="submit" id="deletebtn<?php echo $dataHariIni->id ?>" name="deletebtn<?php echo $dataHariIni->id ?>" class="alert button" value="Delete">
                                </form><div>
                            </div>
                           </div>
                    </div>
					</div>
                <?php } ?>
            </div>
 <!--  =====================================================================END MENU EDIT JADWAL =================================================================================== -->
            <?php $this->load->view('templates/script_foundation'); ?>
    </body>
</html>																													