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
<html>

<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <link href="https://fonts.googleapis.com/css?family=Raleway:400,700" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <?= $this->Html->css(['normalize.min', 'milligram.min', 'cake', 'home']) ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="collapse navbar-collapse" id="navbarSupportedContent">

        <?= $this->Html->image('/img/logo-unitedsuite1.png', [
            "id" => "logo-menu",
            "alt" => "Logo Unitedsuite",
            "url" => '/',
        ]) ?>

        <ul class="navbar-nav ml-5 mt-8">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false">
                    À propos
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown" style="font-size: 1.3rem;">
                    <?= $this->Html->linkFromPath('Who are we ?', 'Pages::aboutUs', ['class' => 'dropdown-item']); ?>
                    <?= $this->Html->linkFromPath('News', 'Pages::news', ['class' => 'dropdown-item']); ?>
                    <div class="dropdown-divider"></div>
                    <?= $this->Html->linkFromPath('Partners', 'Pages::partners', ['class' => 'dropdown-item']); ?>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="<?= $this->Url->build('/employees') ?>">Employees</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="<?= $this->Url->build('/departments') ?>">Departments</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="<?= $this->Url->build('/womenAtWork') ?>">Women At Work</a>
            </li>
        </ul>
    </div>
</nav>
<?= $this->Flash->render() ?>
<?= $this->fetch('content') ?>
<footer>
</footer>
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
