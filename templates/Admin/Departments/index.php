<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Department[]|\Cake\Collection\CollectionInterface $departments
 */
?>
<div class="departments index content col-95 mt-5 mx-auto">
    <?= $this->Html->link(__('New Department'), [
        'action' => 'add'
    ], [
        'class' => 'button float-right btn-blue'
    ]) ?>

    <h3><?= __('Departments') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('dept_no') ?></th>
                    <th><?= $this->Paginator->sort('dept_name') ?></th>
                    <th><?= $this->Paginator->sort('description') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($departments as $department): ?>
                <tr>
                    <td><?= h($department->dept_no) ?></td>
                    <td><?= h($department->dept_name) ?></td>
                    <td><?= $this->Text->Truncate(h($department->description),130) ?></td>
                    <td class="actions">
                        <!-- Button View Dept -->
                        <?= $this->Html->link(__('View'), [
                            'prefix' => 'Admin',
                            'action' => 'view',
                            $department->dept_no
                        ]) ?>

                        <!-- Button Edit Dept -->
                        <?= $this->Html->link(__('Edit'), [
                            'prefix' => 'Admin',
                            'action' => 'edit',
                            $department->dept_no
                        ]) ?>

                        <!-- Button Delete Dept -->
                        <?= $this->Form->postLink(__('Delete'), [
                            'prefix' => 'Admin',
                            'action' => 'delete',
                            $department->dept_no
                        ], [
                            'confirm' => __('Are you sure you want to delete # {0}?', $department->dept_no)
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
