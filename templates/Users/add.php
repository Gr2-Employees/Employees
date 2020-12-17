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
            <fieldset>
                <legend><?= __('Sign Up') ?></legend>
                <?php
                    echo $this->Form->control('email');
                    echo $this->Form->control('password');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit'), ["id" => "btn-form-add-user"]) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
