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
				<div class="table-scroll">
					<table>
						<tr> 
							<td></td> <td>Senin</td> <td>Selasa</td> <td>Rabu</td> <td>Kamis</td> <td>Jumat</td>
						</tr>
						<tr>
							<td>7-8</td> <td></td>  <td></td>  <td></td> <td></td>  <td></td> 
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
		<div class="reveal" id="asd" data-reveal>test </div>
                                        <a data-open="asd"><i class="fi-eye"></i></a>
        <?php $this->load->view('templates/script_foundation'); ?>
		
		
		
		
	</body>
</html>