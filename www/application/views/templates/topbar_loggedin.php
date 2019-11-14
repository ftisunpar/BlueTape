<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!--<div class="title-bar" data-responsive-toggle="navigation-menu" data-hide-for="medium">-->
<!--    <button class="menu-icon" type="button" data-toggle></button>-->
<!--    <div class="title-bar-title"><img src="/public/img/logo.png" class="textsized" alt="B"/></div>-->
<!--</div>-->

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#"><img src="/public/img/logo.png" width="50"/></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <?php foreach ($this->Auth_model->getUserInfo()['modules'] as $module): ?>
                <li <?= $module == $currentModule ? ' class="nav-item active"' : ' class="nav-item "' ?>><a class="nav-link" href="/<?= $module ?>"><?= $this->config->item('module-names')[$module] ?></a></li>
            <?php endforeach; ?>
        </ul>
        <ul class="navbar-nav form-inline">
            <li class="nav-item">
                <a class="nav-link" href="/auth/logout">Logout</a>
            </li>
        </ul>
    </div>
</nav>




&nbsp;