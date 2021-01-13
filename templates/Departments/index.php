<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Department[]|\Cake\Collection\CollectionInterface $departments
 */
?>
<div class="departments index content col-95 mt-5 mx-auto">
    <h3><?= __('Departments') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort(__('N°')) ?></th>
                    <th><?= $this->Paginator->sort(__('Nom')) ?></th>
                    <th><?= $this->Paginator->sort(__('Description')) ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($departments as $department): ?>
                <tr>
                    <td><?= h(strtoupper($department->dept_no)) ?></td>
                    <td><?= h($department->dept_name) ?></td>
                    <td><?= $this->Text->Truncate(h($department->description),130) ?></td>

                    <td class="actions">
                        <!-- View Department link -->
                        <?= $this->Html->link(__('View'), [
                            'action' => 'view', $department->dept_no
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
