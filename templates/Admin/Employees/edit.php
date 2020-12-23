<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Employee $employee
 */
?>
<div class="row"
     style="min-height:91vh;background-image: url('/img/login-background-min.jpg');background-size: cover;background-position: center;background-repeat: no-repeat">
    <div class="column-responsive column-80" style="margin: 50px auto auto auto;">
        <div class="employees form content" style="position: relative;padding: 4rem">
            <?= $this->Form->create($employee) ?>
            <fieldset>
                <legend><?= __('Edit Employee') ?></legend>
                <!-- Button admin/dept/index -->
                <?= $this->Html->link(__('List Employees'), [
                    'prefix' => 'Admin',
                    'action' => 'index'
                ], [
                    'class' => 'button', 'style' => "background-color: #2A6496;border-color: #2A6496;position: absolute;top: 40px;right: 50px"
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
            <?= $this->Form->button(__('Submit'), ['style' => "margin-left: 15px;background-color: #2A6496;border-color: #2A6496"]) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
