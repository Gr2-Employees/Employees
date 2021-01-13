<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Employee[]|\Cake\Collection\CollectionInterface $employees
 */
//$cellMenWomenRatio = $this->cell('Inbox');
?>
<div class="employees index content col-95 mt-5 mx-auto">
    <h3><?= __('Employees') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
            <tr class="tr-th-employee">
                <th><?= $this->Paginator->sort('emp_no') ?></th>
                <th><?= $this->Paginator->sort('birth_date') ?></th>
                <th><?= $this->Paginator->sort('first_name') ?></th>
                <th><?= $this->Paginator->sort('last_name') ?></th>
                <th><?= $this->Paginator->sort('gender') ?></th>
                <th><?= $this->Paginator->sort('hire_date') ?></th>

                <?php if ($this->Identity->isLoggedIn() && ($this->Identity->get('role') === 'admin')) : ?>
                    <th class="actions"><?= __('Actions') ?></th>
                <?php endif; ?>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($employees as $employee): ?>
                <tr class="tr-td-employee">
                    <td><?= $this->Number->format($employee->emp_no) ?></td>
                    <td><?= h($employee->birth_date) ?></td>
                    <td><?= h($employee->first_name) ?></td>
                    <td><?= h($employee->last_name) ?></td>
                    <td><?= h($employee->gender) ?></td>
                    <td><?= h($employee->hire_date) ?></td>

                    <?php if ($this->Identity->isLoggedIn() && ($this->Identity->get('role') === 'admin')) : ?>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), [
                            'action' => 'view', $employee->emp_no
                        ]) ?>
                    <?php endif; ?>
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
