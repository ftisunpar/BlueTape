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
            <div class="large-12 column">
				<div class="callout">
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
						Jam Mulai <input type="time" name="jam_mulai"> </input>
						Durasi <input type="time" name="durasi"> </input>
						Jenis  
						<select name="jenis_jadwal"> 
							<option> Konsultasi </option>
							<option> Perwalian</option>
							<option> Kelas </option>
						</select>
						Label <input type="text" name="label_jadwal"><br>
						<input type="submit" class="button" value="Submit">
					</form>
				</div>
			</div>
		</div>
		<?php
			echo $request_add_jadwal['jam_mulai'];
		?>
		<?php $this->load->view('templates/script_foundation'); ?>
	</body>
</html>