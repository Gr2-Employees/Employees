<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User[]|\Cake\Collection\CollectionInterface $users
 */
?>
<div class="users index content col-95 mt-5 mx-auto">
    <!-- Search form -->
    <?= $this->Form->create(null, [
        'type' => 'get',
        'url' => [
            'action' => 'index'
        ]
    ]) ?>
    <div class="md-form active-blue mb-3">
        <!-- Search input -->
        <?= $this->Form->control('search', [
            'label' => '',
            'type' => 'text',
            'placeholder' => 'Search...',
            'class' => 'frm-control'
        ]) ?>
    </div>
    <?= $this->Form->end() ?>

    <!-- Add User Button-->
    <?= $this->Html->link(__('New User'), [
        'action' => 'add'
    ], [
        'class' => 'button float-right',
        'style' => "background-color: #2A6496;border-color: #2A6496"
    ]) ?>

    <h3><?= __('Users') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
            <tr>
                <th><?= $this->Paginator->sort('emp_no') ?></th>
                <th><?= $this->Paginator->sort('email') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $this->Number->format($user->emp_no) ?></td>
                    <td><?= h($user->email) ?></td>
                    <td class="actions">
                        <!-- Link to View User -->
                        <?= $this->Html->link(__('View'), [
                            'action' => 'view',
                            $user->emp_no
                        ]) ?>

                        <!-- Link to Edit User -->
                        <?= $this->Html->link(__('Edit'), [
                            'action' => 'edit',
                            $user->emp_no
                        ]) ?>

                        <!-- Link to Delete User -->
                        <?= $this->Form->postLink(__('Delete'), [
                            'action' => 'delete', $user->emp_no
                        ], [
                            'confirm' => __('Are you sure you want to delete # {0}?',
                                $user->emp_no)
                        ]) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>
