<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Employee[]|\Cake\Collection\CollectionInterface $employees
 */
?>
<div class="employees index content" style="width: 95%;margin: 30px auto auto auto">
    <h3><?= __('Qualified employees for Manager position') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
            <tr class="tr-th-employee">
                <th><?= $this->Paginator->sort('emp_no') ?></th>
                <th><?= $this->Paginator->sort('Full Name') ?></th>
                <th><?= $this->Paginator->sort('gender') ?></th>
                <th><?= $this->Paginator->sort('hire_date') ?></th>
                <th><?= $this->Paginator->sort('title') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($employees as $employee): ?>
                <tr class="tr-td-employee">
                    <td><?= $this->Number->format($employee->emp_no) ?></td>
                    <td><?= h($employee->first_name . ' ' . $employee->last_name) ?></td>
                    <td><?= h($employee->gender) ?></td>
                    <td><?= h($employee->hire_date) ?></td>
                    <td><?= h($employee->ti['title']) ?></td>
                    <td class="actions">
                        <?= $this->Form->postLink(__('Assign'), [
                            'action' => 'showQualified',
                            '?' => [
                                'emp' => $employee->emp_no,
                                'dept' => $employee->deem['dept_no']
                            ],
                            $employee->deem['dept_no']
                        ], [
                            'confirm' => __('Are you sure you want to assign employee #{0} as Manager?', $employee->emp_no)
                        ]) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
