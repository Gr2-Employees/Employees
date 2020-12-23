<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<div class="row"
     style="min-height:90vh;background-image: url('/img/login-background-min.jpg');background-size: cover;background-position: center;background-repeat: no-repeat">
            <!--<?=$this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $user->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $user->id), 'class' => 'side-nav-item']
            )?>-->
    <div class="col-7" style="margin:90px auto auto">
        <div class="users form content">
            <?= $this->Form->create($user) ?>
            <fieldset style="position: relative">
                <legend><?= __('Edit User') ?></legend>
                <!-- Button admin/dept/index -->
                <?= $this->Html->link(__('List Users'), [
                    'prefix' => 'Admin',
                    'action' => 'index'
                ], [
                    'class' => 'button', 'style' => "background-color: #2A6496;border-color: #2A6496;position: absolute;top: 0px;right: 0px"

                ]) ?>

                <?php
                    echo $this->Form->control('email');
                    echo $this->Form->control('password');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit'), ['style' => "background-color: #2A6496;border-color: #2A6496"]) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
