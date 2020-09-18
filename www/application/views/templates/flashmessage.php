<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger" role="alert"><?= $_SESSION['error'] ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['info'])): ?>
        <div class="alert alert-primary" role="alert"><?= $_SESSION['info'] ?></div>
    <?php endif; ?>