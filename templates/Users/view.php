<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<div class="row row-styled-background">
    <div class="column-responsive column-50 m-auto">
        <div class="users view content">
            <!-- User Info -->
            <h3><?= h($user->full_name) ?></h3>
            <table>

                <!-- Employee ID -->
                <tr>
                    <th><?= __('User ID') ?></th>
                    <td><?= $this->Number->format($user->emp_no) ?></td>
                </tr>

                <!-- Employee Email -->
                <tr>
                    <th><?= __('Email') ?></th>
                    <td><?= h($user->email) ?></td>
                </tr>

                <!-- Employee Birth Date -->
                <tr>
                    <th><?= __('Birth Date') ?></th>
                    <td><?= h($user->birth_date) ?></td>
                </tr>

                <!-- Employee Hire Date -->
                <tr>
                    <th><?= __('Hire Date') ?></th>
                    <td><?= h($user->hire_date) ?></td>
                </tr>

                <!-- Employee Rôle -->
                <tr>
                    <th><?= __('Rôle') ?></th>
                    <td><?= ucFirst(h($user->role)) ?></td>
                </tr>

                <!-- Employee Department -->
                <tr>
                    <th><?= __('Department') ?></th>
                    <td><?= ucFirst(h($user->department)) ?></td>
                </tr>

                <!-- Employee Title -->
                <tr>
                    <th><?= __('Title') ?></th>
                    <td><?= ucFirst(h($user->title)) ?></td>
                </tr>

                <!-- Employee Highest Salary -->
                <tr>
                    <th><?= __('Salary') ?></th>
                    <td><?=  $this->Number->currency($user->salary)?></td>
                </tr>

                <!-- Reset Password -->
                <tr>
                    <th><?= __('Reset My Password') ?></th>
                    <td> <?= $this->Html->link( __('Reset My Password'), [
                            'action' => 'resetPassword',
                            $user->emp_no
                        ], [
                            'class' => 'button'
                        ]) ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
