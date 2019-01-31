<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><div class="row">
    <?php if (isset($_SESSION['error'])): ?>
        <div class="callout alert"><?= $_SESSION['error'] ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['info'])): ?>
        <div class="callout primary"><?= $_SESSION['info'] ?></div>
    <?php endif; ?>
</div>