<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?><!doctype html>
<html class="no-js" lang="en">
	<?php $this->load->view('templates/script_foundation'); ?>
    <?php $this->load->view('templates/head_loggedin'); ?>
    <body>
        <?php $this->load->view('templates/topbar_loggedin'); ?>
        <?php //$this->load->view('templates/flashmessage'); ?>
		
        <div class="row">
			
            <div class="large-12 column callout">
				<div class="large-4 columns">
					<form method="POST" action="/EntriJadwalDosen/add">
						<input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>" />
						Hari 
						<select name="hari"> 
							<option> Senin </option>
							<option> Selasa </option>
							<option> Rabu </option>
							<option> Kamis</option>
							<option> Jumat </option>
						</select><br>
						Jam Mulai
						<select name="jam_mulai"> 
							<option value="7"> 07:00 </option>
							<option value="8"> 08:00 </option>
							<option value="9"> 09:00 </option>
							<option value="10"> 10:00 </option>
							<option value="11"> 11:00 </option>
							<option value="12"> 12:00 </option>
							<option value="13"> 13:00 </option>
							<option value="14"> 14:00 </option>
							<option value="15"> 15:00 </option>
						</select><br>
					</div>
					<div class=" large-4 columns">
						Durasi
						<select name="durasi"> 
							<option value="1"> 1 jam </option>
							<option value="2"> 2 jam </option>
							<option value="3"> 3 jam </option>
							<option value="4"> 4 jam</option>
							<option value="5"> 5 jam </option>
							<option value="6"> 6 jam </option>
							<option value="7"> 7 jam </option>
							<option value="8"> 8 jam </option>
							<option value="9"> 9 jam </option>
						</select><br>
						Jenis  
						<select name="jenis_jadwal"> 
							<option value="konsultasi"> Konsultasi </option>
							<option value="terjadwal"> Jadwal Kelas</option>
						</select>
					</div>
					<div class="large-4 columns">
						
						Label <input type="text" name="label_jadwal"><br>
						<input type="submit" class="button" value="Submit">
					</form>
				</div>
			</div>
			<!-- ================================================================================================================================================== -->
			
			
			email : <?php echo $dataJadwal[0]->user;?>
			<div class="table-scroll" id="jadwal_table">
				<table>
					<?php
						$nama_hari=['Senin','Selasa','Rabu','Kamis','Jumat'];
						?><tr> <td style='width:10%'></td>
						<?php
							for($i=0;$i<5;$i++){
								echo "<td style='width:18%'> $nama_hari[$i] </td>";
							}
						?>
					</tr>
					<?php
						
						for($i=7; $i<17 ;$i++){
							echo "<tr><td>".$i."-".($i+1);
							for($j=0;$j<5;$j++){
								echo"<td align='center'>"."</td>";
							}
						} 
						$rowIdx=1;
						for($i=7; $i<17 ;$i++){
							foreach($dataJadwal as $dataHariIni){
								$colIdx=1;
								
								//kode dibawah untuk mewarnai tabel
								for($j=0;$j<5;$j++){ 
									if($dataHariIni!=null){
										if($dataHariIni->hari==$nama_hari[$j]){
											$jam_selesai = $dataHariIni->jam_mulai + $dataHariIni->durasi;
											if($dataHariIni->jenis=="konsultasi"){
												$color="#FEFF00";
											}
											else{
												$color="#92D14F"; 
											}
											if($i >= $dataHariIni->jam_mulai && $i < $jam_selesai){
											?>
											<script type="text/javascript">
												var table = document.getElementById('jadwal_table');
												var rows = table.getElementsByTagName('tr')
												rows[<?php echo $rowIdx; ?>].cells[<?php echo ($colIdx); ?>].style.backgroundColor = "<?php echo $color; ?>";
											</script>
											<?php
												if( ($i - $dataHariIni->jam_mulai) ==(int)(($dataHariIni->durasi)/2) || ($dataHariIni->durasi)==1){ //menampilkan label ditengah-tengah
												?>
												<script>
													rows[<?php echo $rowIdx; ?>].cells[<?php echo ($colIdx); ?>].innerHTML= "<?php echo $dataHariIni->label ?>";
													$(document).ready(function() {
														$('#buttona1').click(function(e) {        // Button which will activate our modal
															$('#edit').foundation('open');		  // Modal to show
															return false;
														});
													});
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
				<a class="button" id="buttona1">Click me for another modal!</a>
			</div>
			
			<div id="edit" class="reveal" data-reveal>
				<form method="POST" action="/EntriJadwalDosen/edit">
					<input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>" />
					Hari 
					<select name="hari"> 
						<option> Senin </option>
						<option> Selasa </option>
						<option> Rabu </option>
						<option> Kamis</option>
						<option> Jumat </option>
					</select><br>
					Jam Mulai
					<select name="jam_mulai"> 
						<option value="7"> 07:00 </option>
						<option value="8"> 08:00 </option>
						<option value="9"> 09:00 </option>
						<option value="10"> 10:00 </option>
						<option value="11"> 11:00 </option>
						<option value="12"> 12:00 </option>
						<option value="13"> 13:00 </option>
						<option value="14"> 14:00 </option>
						<option value="15"> 15:00 </option>
						<option value="15"> 16:00 </option>
					</select><br>
					Durasi
					<select name="durasi"> 
						<option value="1"> 1 jam </option>
						<option value="2"> 2 jam </option>
						<option value="3"> 3 jam </option>
						<option value="4"> 4 jam</option>
						<option value="5"> 5 jam </option>
						<option value="6"> 6 jam </option>
						<option value="7"> 7 jam </option>
						<option value="8"> 8 jam </option>
						<option value="9"> 9 jam </option>
					</select><br>
					Jenis  
					<select name="jenis_jadwal"> 
						<option value="konsultasi"> Konsultasi </option>
						<option> Perwalian</option>
						<option value="terjadwal"> Kelas </option>
					</select>
					<input type="submit" class="button" value="Save">
				</div>
				
				<?php $this->load->view('templates/script_foundation'); ?>
			</body>
		</html>											