<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?><!doctype html>
<html class="no-js" lang="en">
    <?php $this->load->view('templates/head_loggedin'); ?>
    <body>
        <?php $this->load->view('templates/topbar_loggedin'); ?>
        <?php //$this->load->view('templates/flashmessage'); ?>
		
		
		
		<div class="row">
		
			<ul class="tabs" data-tab>
				<li class="tab-title active"><a href="#panel1">Tab 1</a></li>
  <li class="tab-title"><a href="#panel2">Tab 2</a></li>
  <li class="tab-title"><a href="#panel3">Tab 3</a></li>
  <li class="tab-title"><a href="#panel4">Tab 4</a></li>
</ul>
<div class="tabs-content">
  <div class="content active" id="panel1">
    <p>This is the first panel of the basic tab example. You can place all sorts of content here including a grid.</p>
  </div>
  <div class="content" id="panel2">
    <p>This is the second panel of the basic tab example. This is the second panel of the basic tab example.</p>
  </div>
  <div class="content" id="panel3">
    <p>This is the third panel of the basic tab example. This is the third panel of the basic tab example.</p>
  </div>
  <div class="content" id="panel4">
    <p>This is the fourth panel of the basic tab example. This is the fourth panel of the basic tab example.</p>
  </div>
</div>
		
			<?php
					//=================================================Group data per user======================================
					$dataJadwalPerUser=array();
					foreach($dataJadwal as $key => $indexValue){
						$dataJadwalPerUser[$indexValue->user][$key] = $indexValue;  // dimensi pertama indexnya adalah user
					}
					//==========================================================================================================
				?>
			
			<ul class="tabs" data-tab role="tablist">
				<?php
					$idx=0;
					foreach($dataJadwalPerUser as $tempRow){
						if($idx==0){
						?>
						<li class="tab-title active" role="presentation"><a href="#panel<?php echo $idx; ?>" role="tab" tabindex="0" aria-selected="true" aria-controls="panel<?php echo $idx; ?>">Tab Tabel <?php echo ($idx+1); ?></a></li>
					<?php
						}
						else{
							?>
						<li class="tab-title" role="presentation"><a href="#panel<?php echo $idx; ?>" role="tab" tabindex="0" aria-selected="true" aria-controls="panel<?php echo $idx; ?>">Tab Tabel <?php echo ($idx+1); ?></a></li>
					<?php
						}
					$idx++;	
					}
				?>
			</ul>
			
			<div class="large-12 column">
				<?php  
					$idx=0;
					foreach($dataJadwalPerUser as $currRow){
					?>
					Pemilik : <?php echo $currRow[$idx]->user;?>
					<div class="table-scroll tabs-content" id="jadwal_table<?php echo $idx; ?>">
						<table role="tabpanel" aria-hidden="true" class="content" id="panel<?php echo $idx; ?> " >
							<?php
								$nama_hari=['Senin','Selasa','Rabu','Kamis','Jumat'];
								?><tr> <td style='width:10%'></td>
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
						<?php
							$idx++;
						}
					?>
				</div> 
				<a href="/LihatJadwalDosen/export" class="button">Export to XLS</a>
			</div>
		</div>
		<!--	------------------------------------------------------------Tampilan Edit Pop-up ----------------------------------------------------------------- -->
		<div class="reveal" id="edit" data-reveal>
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
					<option> Konsultasi </option>
					<option> Perwalian</option>
					<option> Kelas </option>
				</select>
				Label <input type="text" name="label_jadwal"><br>
				<input type="submit" class="button" value="Submit">
			</form>
			<button class="close-button" data-close aria-label="Tutup" type="button">
				<span aria-hidden="true">&times;</span>
			</button>
			</div>
			<!--	----------------------------------------------------------------------------------------------------------------------------- -->
			
			
			<?php $this->load->view('templates/script_foundation'); ?>
			
			
		</body>
	</html>
