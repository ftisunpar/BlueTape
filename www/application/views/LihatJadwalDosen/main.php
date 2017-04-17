<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?><!doctype html>
<html class="no-js" lang="en">
    <?php $this->load->view('templates/head_loggedin'); ?>
    <body>
        <?php $this->load->view('templates/topbar_loggedin'); ?>
        <?php //$this->load->view('templates/flashmessage'); ?>
		
		
		<div class="row">
			
			<div class="large-12 column">
				<ul class="tabs" data-tabs id="tab_jadwal">
					<?php
						$idx=0;
						foreach($dataJadwalPerUser as $currRow){
							if($idx==0){
								?>
							<li class="tabs-title is-active"><a href="#hal<?php echo $idx; ?>" aria-selected="true"><?php foreach($currRow as $data ){echo $data->name; break; } ?></a></li> <!-- menggunakan foreach untuk mendapatkan nama dosen karena key pada dimensi kedua bisa loncat-loncat (misal 3,5,19) -->
							<?php
							}
							else{
							?>
							<li class="tabs-title"><a a href="#hal<?php echo $idx; ?>"><?php foreach($currRow as $data ){echo $data->name; break; } ?></a></li>
							<?php
							}
							$idx++;	
						}
					?>
				</ul>
				
				
				<div class="tabs-content" data-tabs-content="tab_jadwal">
					<?php  
						$idx=0;
						foreach($dataJadwalPerUser as $currRow){
						?>
						
						<div class="table-scroll" id="jadwal_table<?php echo $idx; ?>">
							<?php
								if($idx==0){
								
								 echo '<div class="tabs-panel is-active" id="hal'.$idx.'">';
								?>
									<?php
									}
									else{
									?>
									<div class="tabs-panel" id="hal<?php echo $idx;?>">
										<?php
										}
										
									?>
									<table id="tabel<?php echo $idx; ?>">
										<?php $nama_hari=['Senin','Selasa','Rabu','Kamis','Jumat']; ?>
										<tr> 
											<td style='width:10%'>
											</td>
												<?php
													for($i=0;$i<5;$i++){
														echo "<td style='width:18%'>".$nama_hari[$i]."</td>";
													}
												?>
										</tr>
										<?php
											$rowIdx=1;
											for($i=7; $i<17 ;$i++){
												echo "<tr><td>".$i."-".($i+1);
												for($j=0;$j<5;$j++){
													echo"<td align='center'>"."</td>";
												}
											} 
											for($i=7; $i<17 ;$i++){
												foreach($currRow as $dataHariIni){
													$colIdx=1;
													for($j=0;$j<5;$j++){
														if($dataHariIni!=null){
															if($dataHariIni->hari==$nama_hari[$j]){
																$jam_selesai = $dataHariIni->jam_mulai + $dataHariIni->durasi;
																if($dataHariIni->jenis=="konsultasi"){
																	$color="#FEFF00";
																}
																else if($dataHariIni->jenis=="kelas"){
																	$color="#FFFFFF";
																}
																else{
																	$color="#92D14F"; //#c1c1c1
																}
																if($i >= $dataHariIni->jam_mulai && $i < $jam_selesai){
																?>
																<script type="text/javascript">
																	var table = document.getElementById('jadwal_table<?php echo $idx; ?>');
																	var rows = table.getElementsByTagName('tr')
																	rows[<?php echo $rowIdx; ?>].cells[<?php echo ($colIdx); ?>].style.backgroundColor = "<?php echo $color; ?>";
																</script>
																<?php
																	if( ($i - $dataHariIni->jam_mulai) ==(int)(($dataHariIni->durasi)/2) || ($dataHariIni->durasi)==1){ // If ini untuk menampilkan label ditengah-tengah
																	?>
																	<script>
																		rows[<?php echo $rowIdx; ?>].cells[<?php echo ($colIdx); ?>].innerHTML= "<?php echo $dataHariIni->label ?>";
																	</script>
																	<?php
																	}
																}
															}
														}
														$colIdx++;
													}
												}
												$rowIdx++;
											} 
										?>
									</table>
								</div> 
								<?php
									
									$idx++;
								}
								
							?>
							
							<a href="/LihatJadwalDosen/export/" class="button">Export to XLS</a>
						</div>
					</div>
				</div>
				
			
			
			<?php $this->load->view('templates/script_foundation'); ?>
			
			
		</body>
	</html>
