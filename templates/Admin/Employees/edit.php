<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Employee $employee
 */
?>
<div class="row row-styled-background">
    <div class="column-responsive column-80 mt-5 mx-auto">
        <div class="employees form content position-relative p-5">
            <?= $this->Form->create($employee) ?>
            <fieldset>
                <legend><?= __('Edit Employee') ?></legend>
                <!-- Button admin/dept/index -->
                <?= $this->Html->link(__('List Employees'), [
                    'prefix' => 'Admin',
                    'action' => 'index'
                ], [
                    'class' => 'button btn-blue position-absolute', 'style' => "top: 40px;right: 40px"
                ]) ?>
                <div class="row">
                    <div class="column">
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
                    </div>
                </div>
            </fieldset>
            <?= $this->Form->button(__('Submit'), ['class'=>'btn-blue ml-3']) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
