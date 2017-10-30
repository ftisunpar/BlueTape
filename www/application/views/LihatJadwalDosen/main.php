<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!doctype html>
<html class="no-js" lang="en">
    <?php $this->load->view('templates/head_loggedin'); ?>
    <body>
        <?php $this->load->view('templates/topbar_loggedin'); ?>
        <?php $this->load->helper('url'); ?>
        <?php $this->load->view('templates/script_foundation'); ?>
        <?php //$this->load->view('templates/flashmessage'); ?>


        <div class="row">

            <div class="large-12 column">
                <ul class="tabs" data-tabs id="tab_jadwal">
                    <?php
                    $idx = 0;
                    foreach ($dataJadwalPerUser as $currRow) {
                        if ($idx == 0) {
                            ?>
                            <li class="tabs-title is-active"><a href="#hal<?php echo $idx; ?>" aria-selected="true"><?php foreach ($currRow as $data) {
                        echo $data->name;
                        break;
                    } ?></a></li> 
                            <?php
                        } else {
                            ?>
                            <li class="tabs-title"><a a href="#hal<?php echo $idx; ?>"><?php foreach ($currRow as $data) {
                        echo $data->name;
                        break;
                    } ?></a></li>
        <?php
    }
    $idx++;
}
?>
                </ul>


                <div class="tabs-content" data-tabs-content="tab_jadwal">
                        <?php
                        $idx = 0;
                        foreach ($dataJadwalPerUser as $currRow) {
                            ?>

                        <div class="table-scroll" id="jadwal_table<?php echo $idx; ?>">
                            <?php
                            if ($idx == 0) {
                                echo '<div class="tabs-panel is-active" id="hal' . $idx . '">';
                                ?>
                                    <?php
                                } else {
                                    ?>
                                <div class="tabs-panel" id="hal<?php echo $idx; ?>">
        <?php
    }
    ?>
                                <table id="tabel<?php echo $idx; ?>" border=1>
                                    <!-- 						---------------------- GENERATE TEMPLATE TABEL 		------------------------------------ -->
                                    <tr> 
                                        <td style='width:10%'>
                                        </td>
                                        <?php
                                        for ($i = 0; $i < 5; $i++) {
                                            echo "<td style='width:18%'>" . $namaHari[$i] . "</td>";
                                        }
                                        ?>
                                    </tr>
                                    <?php
                                    $cellRowID = 1;
                                    for ($i = 7; $i < 17; $i++) {
                                        echo "<tr><td>" . $i . "-" . ($i + 1);
                                        $cellColID = 1;
                                        for ($j = 0; $j < 5; $j++) {
                                            echo"<td align='center' id='t" . $idx . "cell" . $cellRowID . "-" . $cellColID . "'>" . "</td>";
                                            $cellColID++;
                                        }
                                        $cellRowID++;
                                    }

                                    // MEWARNAI TABEL
                                    $rowIdx = 1;
                                    $cellRowID = 0;
                                    //	----------------------------------------------------------------------------------------------------------------------- 

                                    foreach ($currRow as $dataHariIni) {
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
                                        <script >

                                            var table = document.getElementById('jadwal_table<?php echo $idx; ?>');

                                            var $cellLocation = "#t<?php echo $idx; ?>cell<?php echo $rowIdx; ?>-<?php echo $colIdx; ?>";

                                            $($cellLocation).css('background-color', '<?php echo $color; ?>');
                                            $($cellLocation).attr('rowspan', <?php echo $dataHariIni->durasi ?>);

                                            //menghapus cell-cell yang tergeser karena rowspan
                                            for (i = <?php echo ($rowIdx + 1); ?>; i < <?php echo ($rowIdx + $dataHariIni->durasi); ?>; i++) {
                                                $("#t<?php echo $idx; ?>cell" + i + "-" +<?php echo $colIdx; ?>).remove();
                                            }
                                            $($cellLocation).html("<?php echo $dataHariIni->label ?>");


                                        </script>
                                        <?php
                                    }
                                    ?>
                                </table>
								<?php
								if($currRow!=null){
									$palingBaru=date('Y-m-d H:i:s', 0); // datetime paling awal di PHP (1 Januari 1970 00:00:00) agar langsung tertimpa di foreach di bawah
									foreach($currRow as $perJadwal){
										if($palingBaru < $perJadwal->lastUpdate){
												$palingBaru = $perJadwal->lastUpdate;
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
							<a href="/LihatJadwalDosen/export/" class="button">Ekspor ke XLS</a>
                            </div> 
                            <?php
                            $idx++;
                        }
                        ?>
                        
                    </div>
                </div>
            </div>

            <?php $this->load->view('templates/script_foundation'); ?>
    </body>
</html>
