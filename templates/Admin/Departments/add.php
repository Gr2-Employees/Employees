<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Department $department
 */
?>
<div class="row"
     style="min-height:91vh;background-image: url('/img/login-background-min.jpg');background-size: cover;background-position: center;background-repeat: no-repeat">
    <div class="column-responsive column-60" style="margin: auto">
        <div class="departments form content" style="position: relative; padding:4rem">
            <?= $this->Form->create($department, ["type" => "file"]) ?>
            <fieldset>
                <legend><?= __('Add Department') ?></legend>
                <!-- Button admin/dept/index -->
                <?= $this->Html->link(__('List Departments'), [
                    'prefix' => 'Admin',
                    'action' => 'index'
                ], [
                    'class' => 'button', 'style' => "background-color: #2A6496;border-color: #2A6496;position: absolute;top: 40px;right: 40px"
                ]) ?>
                <?php
                echo $this->Form->control('dept_name', ['required' => 'true']);
                echo $this->Form->control('description', ['required' => 'true']);
                echo $this->Form->control('address', ['required' => 'true']);
                echo $this->Form->control('rules');
                echo $this->Form->control('picture', [
                    'required' => 'true',
                    'type' => 'file'
                ]); ?>
            </fieldset>
            <?= $this->Form->button(__('Submit'), ['style' => "background-color: #2A6496;border-color: #2A6496"]) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
