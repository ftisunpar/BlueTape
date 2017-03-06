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
                   
            </div>
        </div>
        <?php $this->load->view('templates/script_foundation'); ?>
    </body>
</html>