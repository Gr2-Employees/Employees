<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Department $department
 */
?>
<div class="row"
     style="min-height:91vh;background-image: url('/img/login-background-min.jpg');background-size: cover;background-position: center;background-repeat: no-repeat">
    <div class="column-responsive column-80" style="margin: 50px auto auto auto;">
        <div class="departments form content" style="position: relative">
            <?= $this->Form->create($department, ["type" => "file"]) ?>
            <fieldset class="row">

                <legend><?= __('Edit Department') ?></legend>
                <!-- Button admin/dept/index -->
                <?= $this->Html->link(__('List Departments'), [
                    'prefix' => 'Admin',
                    'action' => 'index'
                ], [
                    'style' => 'position: absolute;right:20px;top:30px;padding:10px;background-color:#2A6496;border-radius:3px;color:white;font-size:1.8rem'
                ]) ?>

                <div class="col-6">
                    <?php
                    echo $this->Form->control('dept_name');
                    echo $this->Form->control('description');
                    echo $this->Form->control('address');
                    echo $this->Form->control('rules');
                    ?>
                </div>
                <div class="col-6" style="text-align: center;padding-top: 30px">
                    <?= $this->Html->image('/img/uploads/dept_pictures/' . $department->picture, [
                        'alt' => 'Manager du département ' . $department->dept_name . '.',
                        'class' => 'manager-picture',
                        'style' => 'margin-bottom: 20px'
                    ]) ?>

                    <?php
                    echo $this->Form->control('picture', [
                        'type' => 'file', 'required' => 'false'
                    ]); ?>
                </div>
            </fieldset>
            <?= $this->Form->button(__('Submit'), ['style' => "margin-left: 15px;background-color: #2A6496;border-color: #2A6496"]) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
