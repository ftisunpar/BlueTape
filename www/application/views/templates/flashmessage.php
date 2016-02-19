<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><?php if (isset($_SESSION['error'])): ?>
    <blockquote><?= $_SESSION['error'] ?></blockquote>
<?php endif; ?>
<?php if (isset($_SESSION['info'])): ?>
    <blockquote><?= $_SESSION['info'] ?></blockquote>
<?php endif; ?>
