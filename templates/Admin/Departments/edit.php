<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Department $department
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <!-- Button delete admin/dept-->
            <?= $this->Form->postLink(__('Delete'), [
                'prefix' => 'Admin',
                'action' => 'delete',
                $department->dept_no
            ], [
                'confirm' => __('Are you sure you want to delete # {0}?',
                    $department->dept_no),
                'class' => 'side-nav-item'
            ]) ?>

            <!-- Button admin/dept/index -->
            <?= $this->Html->link(__('List Departments'), [
                'prefix' => 'Admin',
                'action' => 'index'
            ], [
                'class' => 'side-nav-item'
            ]) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="departments form content">
            <?= $this->Form->create($department, ["type" => "file"]) ?>
            <fieldset>
                <legend><?= __('Edit Department') ?></legend>
                <?php
                echo $this->Form->control('dept_name');?>
                <div class="col-6">
                    <?= $this->Html->image('/img/uploads/dept_pictures/' . $department->picture, [
                        'alt' => 'Manager du département ' . $department->dept_name . '.',
                        'class' => 'manager-picture'
                    ]) ?>
                </div>
                <?php
                echo $this->Form->control('picture', [
                'type' => 'file','required'=>'false',
                ]);
                echo $this->Form->control('description');
                echo $this->Form->control('address');
                echo $this->Form->control('rules');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
