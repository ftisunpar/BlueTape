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
				<div class="medium-6 column">
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
					</div>
					<div class="medium-6 column">
						Jenis  
						<select name="jenis_jadwal"> 
							<option value="konsultasi"> Konsultasi </option>
							<option value="terjadwal"> Jadwal Kelas</option>
						</select>
						Label <input type="text" name="label_jadwal"><br>
						<input type="submit" class="button" value="Submit">
					</form>
				</div>
			</div>
			<!-- ================================================================================================================================================== -->
			
			<div class="large-12 column table-scroll callout">
				<table>
					<?php
						$nama_hari=['Senin','Selasa','Rabu','Kamis','Jumat'];
						?><tr> <td></td>
						<?php
							for($i=0;$i<5;$i++){
								echo "<td>".$nama_hari[$i]."</td>";
							}
						?>
					</tr>
					<?php
						for($i=7; $i<17 ;$i++){
							echo "<tr><td>".$i."-".($i+1);
							for($j=0;$j<5;$j++){
								if($request_add_jadwal!=null){
									if($request_add_jadwal['hari']==$nama_hari[$j]){
										$temp=$request_add_jadwal['jam_mulai']+$request_add_jadwal['durasi'];
										if($request_add_jadwal['jenis_jadwal']=="konsultasi"){
											$color="#FEFF00";
										}
										else{
											$color="#92D14F";
										}
										if($i>=$request_add_jadwal['jam_mulai'] && $i<$temp){
											echo"<td bgcolor=$color data-open='edit'>"."</td>";
										}
										else{
											echo"<td>"."</td>";
										}
									}
									else{
										echo"<td>"."</td>";
									}
								}
								else{
									echo"<td bgcolor=''>"."</td>";
								}
							}
							echo "</tr>";
						}
					?>
				</table>
			</div>
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