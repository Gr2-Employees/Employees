<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<div class="row m-5">
    <aside class="col-15">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <!-- Link to admin/users/index -->
            <?= $this->Html->link(__('List Users'), [
                'action' => 'index'
            ], [
                'class' => 'side-nav-item'
            ]) ?>

            <!-- Link to Add User -->
            <?= $this->Html->link(__('New User'), [
                'action' => 'add'
            ], [
                'class' => 'side-nav-item'
            ]) ?>

            <!-- Link to Edit User -->
            <?= $this->Html->link(__('Edit User'), [
                'action' => 'edit',
                $user->emp_no
            ], [
                'class' => 'side-nav-item'
            ]) ?>

            <!-- Link to Delete User -->
            <?= $this->Form->postLink(__('Delete User'), [
                'action' => 'delete', $user->emp_no
            ], [
                'confirm' => __('Are you sure you want to delete # {0}?',
                $user->emp_no),
                'class' => 'side-nav-item'
            ]) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="users view content">
            <h3><?= h($user->emp_no) ?></h3>
            <table>
                <tr>
                    <th><?= __('Employee ID') ?></th>
                    <td><?= $this->Number->format($user->emp_no) ?></td>
                </tr>
                <tr>
                    <th><?= __('Email') ?></th>
                    <td><?= h($user->email) ?></td>
                </tr>
                <tr>
                    <th><?= __('Password') ?></th>
                    <td><?= h($user->password) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
