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

            <!-- TODO: Add auth to that user, only that user can edit the profile -->
            <?= $this->Html->link(__('Edit User'), [
                'action' => 'edit', $user->emp_no
            ], [
                'class' => 'side-nav-item'
            ]) ?>

            <!-- TODO: Add auth to that user, only that user can delete the profile -->
            <?= $this->Form->postLink(__('Delete User'), [
                'action' => 'delete', $user->emp_no
            ], [
                'confirm' => __('Are you sure you want to delete # {0}?', $user->emp_no),
                'class' => 'side-nav-item'
            ]) ?>

        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="users view content">
            <h3><?= h($user->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('User ID') ?></th>
                    <td><?= $this->Number->format($user->emp_no) ?></td>
                </tr>
                <tr>
                    <th><?= __('Email') ?></th>
                    <td><?= h($user->email) ?></td>
                </tr>
                <tr>
                    <th><?= __('Change Password') ?></th>
                    <td><?= $this->Html->link('Change password', [
                            'action' => 'pwdChange',
                            $user->emp_no
                        ], [
                            'class' => 'btn button'
                        ])?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
