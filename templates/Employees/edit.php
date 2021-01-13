<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Employee $employee
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(__('Delete'), [
                'action' => 'delete', $employee->emp_no
            ], [
                'confirm' => __('Are you sure you want to delete # {0}?', $employee->emp_no),
                'class' => 'side-nav-item'
            ]) ?>
            <?= $this->Html->link(__('List Employees'), [
                'action' => 'index'
            ], [
                'class' => 'side-nav-item'
            ]) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="employees form content">
            <?= $this->Form->create($employee) ?>
            <fieldset>
                <legend>
                    <?= __('Edit Employee') ?>
                </legend>
                <?= $this->Form->control('birth_date') ?>
                <?= $this->Form->control('first_name') ?>
                <?= $this->Form->control('last_name') ?>
                <?= $this->Form->control('gender') ?>
                <?= $this->Form->control('hire_date') ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
