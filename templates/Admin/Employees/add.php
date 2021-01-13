<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Employee $employee
 */
?>
<div class="row row-styled-background">
    <div class="column-responsive column-80 mt-4 mx-auto">
        <div class="employees form content position-relative p-5">
        <?= $this->Form->create($employee, [
            'type' => 'file'
        ]) ?>
        <fieldset class="position-relative">
            <!-- Add employee button -->
            <legend><?= __('Add Employee') ?></legend>
            <?= $this->Html->link(__('List Employees'), [
                'action' => 'index'
            ], [
                'class' => 'button position-absolute',
                'style' => "background-color: #2A6496;border-color: #2A6496;top: 0px;right: 0px"
            ]) ?>

            <!-- Firstname field -->
            <?= $this->Form->control('first_name', [
                'required' => 'true'
            ]) ?>

            <!-- Lastname field -->
            <?= $this->Form->control('last_name', [
                'required' => 'true'
            ]) ?>

            <!-- Gender select field -->
            <?= $this->Form->control('gender', [
                'type' => 'select',
                'options' => [
                    'M' => 'M',
                    'F' => 'F',
                    'Other' => 'Other'
                ]
            ]) ?>

            <!-- Birthday field -->
            <?= $this->Form->control('birth_date', [
                'type' => 'date',
                'required' => 'true'
            ]) ?>

            <!-- Email field -->
            <?= $this->Form->control('email', [
                'required' => 'true'
            ]) ?>

            <!-- Hire date field -->
            <?= $this->Form->control('hire_date', [
                'type' => 'date',
                'required' => 'true'
            ]) ?>

            <!-- Select department field -->
            <?= $this->Form->control('dept_no', [
                'type' => 'select',
                'options' => $departments
            ]) ?>

            <!-- Select title field -->
            <?= $this->Form->control('title', [
                'type' => 'select',
                'options' => $titles
            ]) ?>

            <!-- Picture file input -->
            <?= $this->Form->control('picture', [
                'type' => 'file'
            ]) ?>
            ?>
        </fieldset>
        <?= $this->Form->button(__('Submit'), ['class' => "btn-blue"]) ?>
        <?= $this->Form->end() ?>
    </div>
</div>
</div>
