<?php

if (isset($welcomeMessage)) {
    echo $welcomeMessage;
}
?>

    <!-- TODO: Ajouter des cells pour des quick infos sur les différentes parties du back-office -->
    <!-- Lien vers admin/dept/index-->
<?= $this->Html->link('To departments', [
    'prefix' => 'Admin',
    'controller' => 'Departments',
    'action' => 'index'
]) ?>

<hr/>

    <!-- Lien vers admin/Employees/index-->
<?= $this->Html->link('To Employees', [
    'prefix' => 'Admin',
    'controller' => 'Employees',
    'action' => 'index'
]) ?>

<hr/>

    <!-- Lien vers admin/Users/index-->
<?= $this->Html->link('To Users', [
    'prefix' => 'Admin',
    'controller' => 'users',
    'action' => 'index'
]) ?>
