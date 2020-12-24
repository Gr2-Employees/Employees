<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Department $department
 */
?>
<div class="row row-styled-background">
    <div class="column-responsive column-80 m-auto">
        <div class="departments form content position-relative">
            <?= $this->Form->create($department, ["type" => "file"]) ?>
            <fieldset class="row">

                <legend><?= __('Edit Department') ?></legend>
                <!-- Button admin/dept/index -->
                <?= $this->Html->link(__('List Departments'), [
                    'prefix' => 'Admin',
                    'action' => 'index'
                ], [
                    'class' => 'button btn-blue position-absolute', 'style' => "top: 25px;right: 40px"

                ]) ?>

                <div class="col-6">
                    <?php
                    echo $this->Form->control('dept_name');
                    echo $this->Form->control('description');
                    echo $this->Form->control('address');
                    echo $this->Form->control('rules');
                    ?>
                </div>
                <div class="col-6 pt-3 text-center">
                    <?= $this->Html->image('/img/uploads/dept_pictures/' . $department->picture, [
                        'alt' => 'Photo du département ' . $department->dept_name . '.',
                        'class' => 'manager-picture mb-4'
                    ]) ?>

                    <?php
                    echo $this->Form->control('picture', [
                        'type' => 'file', 'required' => 'false'
                    ]); ?>
                </div>
            </fieldset>
            <?= $this->Form->button(__('Submit'), ["class" => "btn-blue ml-4"]) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
