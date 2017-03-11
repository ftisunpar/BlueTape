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
						Jam Mulai <input type="time" name="jam-mulai"> </input>
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
					<tr> 
						<td></td> <td>Senin</td> <td>Selasa</td> <td>Rabu</td> <td>Kamis</td> <td>Jumat</td>
					</tr>
					<tr>
						<td>7-8</td> <td bgcolor="red"></td>  <td></td>  <td></td> <td></td>  <td></td> 
					</tr>
					<tr>
						<td>8-9</td> <td></td>  <td></td>  <td></td>  <td></td>  <td></td> 
					</tr>
					<tr>
						<td>9-10</td> <td></td>  <td></td>  <td></td>  <td></td>  <td></td> 
					</tr>
					<tr>
						<td>10-11</td> <td></td>  <td></td>  <td></td>  <td></td> <td></td> 
					</tr>
					<tr>
						<td>11-12</td> <td></td>  <td></td>  <td></td>  <td></td>  <td></td> 
					</tr>
					<tr>
						<td>12-13</td> <td></td>  <td></td>  <td></td> <td></td> <td></td> 
					</tr>
					<tr>
						<td>13-14</td> <td></td> <td></td>  <td></td> <td></td> <td></td> 
					</tr>
					<tr>
						<td>14-15</td> <td></td>  <td></td>  <td></td> <td></td> <td></td> 
					</tr>
					<tr>
						<td>15-16</td> <td></td>  <td></td> <td></td>  <td></td>  <td></td> 
					</tr>
					<tr>
						<td>16-17</td> <td></td>  <td></td>  <td></td> <td></td> <td></td> 
					</tr>
				</table>
			</div>
		</div>
	</div>
	
	<?php $this->load->view('templates/script_foundation'); ?>
	
	
	
	
</body>
</html>