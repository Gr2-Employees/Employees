<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Employee[]|\Cake\Collection\CollectionInterface $employees
 */
?>
<div class="employees index content col-95 mt-5 mx-auto">
    <!-- Search form -->
    <?= $this->Form->create(null, [
        'url' => ['action' => 'index'],
        'type' => 'get',
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

    <!-- Add Employee Button -->
    <?= $this->Html->link(__('New Employee'), [
        'action' => 'add'
    ], [
        'class' => 'button float-right btn-blue'
    ]) ?>
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
                <th><?= $this->Paginator->sort('picture') ?></th>
                <th><?= $this->Paginator->sort('email') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($employees as $employee): ?>
                <tr class="tr-td-employee">
                    <td><?= $this->Number->format($employee->emp_no) ?></td>
                    <td><?= h($employee->birth_date->i18nFormat('yyyy-MM-dd')) ?></td>
                    <td><?= h($employee->first_name) ?></td>
                    <td><?= h($employee->last_name) ?></td>
                    <td><?= h($employee->gender) ?></td>
                    <td><?= h($employee->hire_date->i18nFormat('yyyy-MM-dd')) ?></td>
                    <td><?= h($employee->picture) ?></td>
                    <td><?= h($employee->email) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $employee->emp_no]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $employee->emp_no]) ?>
                        <?= $this->Form->postLink(__('Delete'), [
                            'action' => 'delete', $employee->emp_no
                        ], [
                            'confirm' => __('Are you sure you want to delete # {0}?', $employee->emp_no)
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
