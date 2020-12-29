<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Employee $employee
 */
?>
<div class="row row-styled-background">
    <div class="column-responsive column-80 mt-4 mx-auto">
        <div class="employees form content position-relative p-5"">
            <?= $this->Form->create($employee) ?>
            <fieldset class="position-relative">
                <legend><?= __('Add Employee') ?></legend>
                <?= $this->Html->link(__('List Employees'), ['action' => 'index'],
                    ['class' => 'button position-absolute', 'style' => "background-color: #2A6496;border-color: #2A6496;top: 0px;right: 0px"]) ?>

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
            <?= $this->Form->button(__('Submit'), ['class' => "btn-blue"]) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
