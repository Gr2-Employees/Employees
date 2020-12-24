<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<div class="row row row-styled-background">
            <!--<?=$this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $user->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $user->id), 'class' => 'side-nav-item']
            )?>-->
    <div class="col-7 m-auto"">
        <div class="users form content">
            <?= $this->Form->create($user) ?>
            <fieldset class="position-relative">
                <legend><?= __('Edit User') ?></legend>
                <!-- Button admin/dept/index -->
                <?= $this->Html->link(__('List Users'), [
                    'prefix' => 'Admin',
                    'action' => 'index'
                ], [
                    'class' => 'button btn-blue position-absolute', 'style' => "top: 0px;right: 0px"
                ]) ?>

                <?php
                    echo $this->Form->control('email');
                    echo $this->Form->control('password');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit'), ['class' => "btn-blue"]) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
