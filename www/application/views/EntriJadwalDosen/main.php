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
                   
            </div>
        </div>
        
		<div class="reveal" id="asd" data-reveal>test </div>
                                        <a data-open="asd"><i class="fi-eye"></i></a>
        <?php $this->load->view('templates/script_foundation'); ?>
    </body>
</html>