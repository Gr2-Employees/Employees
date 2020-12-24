<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<div id="add-user-background" class="row">
    <div class="col-add">
        <div class="users form content">
            <?= $this->Form->create($user) ?>
            <fieldset class="position-relative">
                <legend><?= __('Add User') ?></legend>
                <!-- Button admin/users/index -->
                <?= $this->Html->link(__('List Users'), [
                    'prefix' => 'Admin',
                    'action' => 'index'
                ], [
                    'class' => 'button btn-blue position-absolute','style' => "top: 0px;right: 0px"
                ]) ?>
                <?php
                echo $this->Form->control('email');
                echo $this->Form->control('password');
                echo $this->Form->control('confPwd', [
                    'label' => __('Confirm the password'),
                    'type' => 'password'
                ]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit'), ["id" => "btn-form-add-user"]) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
