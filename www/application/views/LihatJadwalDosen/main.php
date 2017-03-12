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
				<div class="reveal" id="asd" data-reveal>
					<form method="POST" action="">
						Hari 
						<select name="hari"> 
							<option> Senin </option>
							<option> Selasa </option>
							<option> Rabu </option>
							<option> Kamis</option>
							<option> Jumat </option>
						</select><br>
						Jam Mulai <input type="time" name="jam_mulai"> </input>
						Durasi <input type="time" name="durasi"> </input>
						Jenis  
						<select name="jenis_jadwal"> 
							<option> Konsultasi </option>
							<option> Perwalian</option>
							<option> Kelas </option>
						</select>
						Label <input type="text" name="label_jadwal"><br>
						<input type="submit" class="button" value="Submit"><input type="cancel" class="button" value="Cancel">
					</form>
					<button class="close-button" data-close aria-label="Tutup" type="button">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
			<a data-open="asd">+ Tambah Jadwal</i></a>
			
			<div class="table-scroll">
				<table>
					<?php
						$nama_hari=['Senin','Selasa','Rabu','Kamis','Jumat'];
						?>
						<tr> <td></td>
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
										$temp=idate('H',strtotime($request_add_jadwal['jam_mulai']))+idate('H',strtotime($request_add_jadwal['durasi']));
										if($i>=idate('H', strtotime($request_add_jadwal['jam_mulai'])) && $i<$temp){
											echo"<td bgcolor='#FF0000'>"."</td>";
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
									echo"<td>"."</td>";
								}
							}
							echo "</tr>";
						}
					?>
				</table>
			</div> 
		</div>
	</div>
	
	<?php $this->load->view('templates/script_foundation'); ?>
	
	<script>
		$(document).ready(function(){
			$('#modal-ajax').foundation('reveal');
		});  
	</script>
	
</body>
</html>
