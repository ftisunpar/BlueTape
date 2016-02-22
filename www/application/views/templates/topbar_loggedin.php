<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><div class="title-bar" data-responsive-toggle="navigation-menu" data-hide-for="medium">
    <button class="menu-icon" type="button" data-toggle></button>
    <div class="title-bar-title">B</div>
</div>

<div class="top-bar" id="navigation-menu">
    <div class="top-bar-left">
        <ul class="menu" data-responsive-menu="dropdown">
            <li class="menu-text">B</li>
            <?php foreach ($this->Auth_model->getUserInfo()['modules'] as $module): ?>
                <li<?= $module === $currentModule ? ' class="menu-active"' : '' ?>><a href="/<?= $module ?>"><?= $module ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="top-bar-right">
        <ul class="menu">
            <li><a href="/auth/logout">Logout</a></li>
        </ul>
    </div>
</div>