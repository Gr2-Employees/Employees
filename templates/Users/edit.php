<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>

            <!-- Link to index -->
            <?= $this->Html->link(__('List Users'), [
                'action' => 'index'
            ], [
                'class' => 'side-nav-item'
            ]) ?>

            <!-- Link to delete user -->
            <?= $this->Form->postLink( __('Delete'), [
                'action' => 'delete',
                $user->emp_no
            ], [
                'confirm' => __('Are you sure you want to delete # {0}?', $user->emp_no),
                'class' => 'side-nav-item'
            ]) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="users form content">
            <?= $this->Form->create($user) ?>
            <fieldset>
                <legend><?= __('Edit User') ?></legend>

                    <?= $this->Form->control('Email') ?>
                    <?= $this->Form->control('password') ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
