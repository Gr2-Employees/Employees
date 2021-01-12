<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<div id="add-user-background" class="row">
    <div class="col-add">
        <div class="users form content">
            <?= $this->Form->create(null) ?>
            <fieldset>
                <legend><?= __('Change password') ?></legend>

                <!-- Password field -->
                <?= $this->Form->control('New Password', [
                    'type' => 'password'
                ]) ?>

                <!-- Confirmation Password field -->
                <?= $this->Form->control('confPwd', [
                    'label' => __('Confirm your password'),
                    'type' => 'password'
                ]) ?>
            </fieldset>

            <?= $this->Form->button(__('Submit'), [
                'id' => 'btn-form-add-user'
            ]) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
