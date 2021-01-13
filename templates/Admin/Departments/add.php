<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Department $department
 */
?>
<div class="row row-styled-background">
    <div class="column-responsive column-60 m-auto">
        <div class="departments form content position-relative">
            <?= $this->Form->create($department, ["type" => "file"]) ?>
            <fieldset>
                <legend><?= __('Add Department') ?></legend>
                <!-- Button admin/dept/index -->
                <?= $this->Html->link(__('List Departments'), [
                    'prefix' => 'Admin',
                    'action' => 'index'
                ], [
                    'class' => 'button btn-blue position-absolute', 'style' => "top: 40px;right: 40px"
                ]) ?>

                <!-- Form fields -->
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
            <?= $this->Form->button(__('Submit'), ['class' => "btn-blue"]) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
