<?php

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 * @var \App\View\AppView $this
 */

$cakeDescription = 'CakePHP: the rapid development php framework';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <link href="https://fonts.googleapis.com/css?family=Raleway:400,500,700" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <?= $this->Html->css(['normalize.min', 'milligram.min', 'cake', 'home', 'department-employee', 'womenAtWork', 'user', 'pages', 'dashboard']) ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="collapse navbar-collapse" id="navbarSupportedContent">

        <!-- Logo UnitedSuite -->
        <?= $this->Html->image('/img/logo-unitedsuite1.png', [
            "id" => "logo-menu",
            "alt" => "Logo Unitedsuite",
            "url" => '/',
        ]) ?>

        <ul class="navbar-nav ml-5 mt-8">
            <!-- A propos dropdown menu -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false">
                    À propos
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown" style="font-size: 1.3rem;">
                    <ul id="dropdown-list" style="list-style: none">
                        <li><?= $this->Html->linkFromPath('Who are we ?', 'Pages::aboutUs', ['class' => 'dropdown-item']) ?></li>
                        <li><?= $this->Html->link('News', '/#news', [
                            "controller" => "Pages",
                            "action" => "display"
                        ]) ?></li>
                        <li><?= $this->Html->link('Partners', '/#partners', [
                            "controller" => "Pages",
                            "action" => "display"
                        ]) ?></li>
                    </ul>
                </div>
            </li>

            <!-- Lien vers la page Employees -->
            <?php if ($this->Identity->isLoggedIn()) { ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $this->Url->build('/employees') ?>">Employees</a>
                </li>
            <?php } ?>

            <!-- Lien vers la page Departments -->
            <li class="nav-item">
                <a class="nav-link" href="<?= $this->Url->build('/departments') ?>">Departments</a>
            </li>

            <!-- Lien vers la page Women at Work -->
            <li class="nav-item">
                <a class="nav-link" href="<?= $this->Url->build('/womenAtWork') ?>">Women At Work</a>
            </li>

            <?php if (($this->Identity->get('role') === 'admin') || ($this->Identity->get('role') === 'manager') || ($this->Identity->get('role') === 'comptable')) { ?>
                <!-- Lien vers la page Demands -->
                <li class="nav-item">
                    <?= $this->Html->link('Demands', [
                        'controller' => 'Demands',
                        'action' => 'index'
                    ], [
                        'class' => 'nav-link'
                    ]) ?>
                </li>
            <?php } ?>

            <?php if ($this->Identity->get('role') === 'admin') { ?>
                <!-- Lien vers la page Dashboard -->
                <li class="nav-item">
                    <?= $this->Html->link('Dashboard', [
                        'prefix' => 'Admin',
                        'controller' => 'Dashboard',
                        'action' => 'index'
                    ],
                        [
                            'class' => 'nav-link'
                        ]) ?>
                </li>
            <?php } ?>
        </ul>
        <!-- Auth buttons -->
        <?php
        if ($this->Identity->isLoggedIn()) { ?>
            <!-- Profile icon link -->
            <?php if ($this->Identity->get('role') === 'admin') { ?>
                <?= $this->Html->link(__('<i></i>'), [
                    'prefix' => 'Admin',
                    'controller' => 'Users',
                    'action' => 'view',
                    $this->Identity->get('emp_no')
                ], [
                    "class" => "fas fa-2x fa-user-circle position-absolute",
                    "style" => "right:110px;top:27px;",
                    "escape" => false
                ]) ?>
            <?php } else { ?>
                <?= $this->Html->link(__('<i></i>'), [
                    'controller' => 'Users',
                    'action' => 'view',
                    $this->Identity->get('emp_no')
                ], [
                    "class" => "fas fa-2x fa-user-circle position-absolute",
                    "style" => "right:110px;top:27px;",
                    "escape" => false
                ]) ?>
            <?php } ?>

            <!-- Logout Button -->
            <?= $this->Html->link(__('Logout'), [
                'controller' => 'Users',
                'action' => 'logout'
            ], [
                'class' => 'btn btn-outline-primary my-2 my-sm-0',
                'id' => 'btn-logout'
            ]) ?>
        <?php } else { ?>
            <!-- Log In Button -->
            <?= $this->Html->link(__('Login'), [
                'controller' => 'Users',
                'action' => 'login'
            ], [
                'class' => 'btn btn-primary my-2 my-sm-0',
                'id' => 'btn-login'
            ]) ?>

            <!-- Sign up Button -->
            <?= $this->Html->link(__('Sign up'), [
                'controller' => 'Users',
                'action' => 'signup'
            ], [
                'class' => 'btn btn-outline-primary my-2 my-sm-0',
                'id' => 'btn-signup'
            ]); ?>
        <?php } ?>

    </div>
</nav>
<?= $this->Flash->render() ?>
<?= $this->fetch('content') ?>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js"
        integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s"
        crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/7df36244de.js" crossorigin="anonymous"></script>
</body>
</html>
