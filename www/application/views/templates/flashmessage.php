<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->library('session');
?><?php if (isset($_SESSION['error'])): ?>
    <div class="callout alert"><?= $_SESSION['error'] ?></div>
<?php endif; ?>
<?php if (isset($_SESSION['info'])): ?>
    <div class="callout info"><?= $_SESSION['info'] ?></div>
<?php endif; ?>
