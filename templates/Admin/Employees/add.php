<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Employee $employee
 */
?>
<div class="row row-styled-background">
    <div class="column-responsive column-80 mt-4 mx-auto">
        <div class="employees form content position-relative p-5"
        ">
        <?= $this->Form->create($employee, [
            'type' => 'file'
        ]) ?>
        <fieldset class="position-relative">
            <legend><?= __('Add Employee') ?></legend>
            <?= $this->Html->link(__('List Employees'), [
                'action' => 'index'
            ], [
                'class' => 'button position-absolute',
                'style' => "background-color: #2A6496;border-color: #2A6496;top: 0px;right: 0px"
            ]) ?>

            <?php
            // Firstname field
            echo $this->Form->control('first_name', [
                'required' => 'true'
            ]);

            // Lastname field
            echo $this->Form->control('last_name', [
                'required' => 'true'
            ]);

            // Gender field
            echo $this->Form->control('gender', [
                'type' => 'select',
                'options' => [
                    'M' => 'M',
                    'F' => 'F',
                    'Other' => 'Other'
                ]
            ]);

            // Birthday field
            echo $this->Form->control('birth_date', [
                'type' => 'date',
                'required' => 'true'
            ]);

            // Email field
            echo $this->Form->control('email', [
                'required' => 'true'
            ]);

            // Hire date field
            echo $this->Form->control('hire_date', [
                'type' => 'date',
                'required' => 'true'
            ]);

            // Department select field
            echo $this->Form->control('dept_no', [
                'type' => 'select',
                'options' => $departments
            ]);

            // Title select field
            echo $this->Form->control('title', [
                'type' => 'select',
                'options' => $titles
            ]);

            // Picture field
            echo $this->Form->control('picture', [
                'type' => 'file'
            ]);
            ?>
        </fieldset>
        <?= $this->Form->button(__('Submit'), ['class' => "btn-blue"]) ?>
        <?= $this->Form->end() ?>
    </div>
</div>
</div>
