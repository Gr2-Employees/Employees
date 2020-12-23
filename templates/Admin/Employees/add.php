<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Employee $employee
 */
?>
<div class="row"
     style="min-height:91vh;background-image: url('/img/login-background-min.jpg');background-size: cover;background-position: center;background-repeat: no-repeat">

    <div class="column-responsive column-80" style="margin: 35px auto">
        <div class="employees form content" style="position: relative; padding:4rem">
            <?= $this->Form->create($employee) ?>
            <fieldset style="position: relative">
                <legend><?= __('Add Employee') ?></legend>
                <?= $this->Html->link(__('List Employees'), ['action' => 'index'],
                    ['class' => 'button', 'style' => "background-color: #2A6496;border-color: #2A6496;position: absolute;top: 0px;right: 0px"]) ?>

                <?php
                echo $this->Form->control('birth_date');
                echo $this->Form->control('first_name');
                echo $this->Form->control('last_name');
                echo $this->Form->control('gender');
                echo $this->Form->control('hire_date');
                echo $this->Form->control('picture');
                echo $this->Form->control('email');
                echo $this->Form->control('departments._ids', ['options' => $departments]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit'), ['style' => "background-color: #2A6496;border-color: #2A6496"]) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
