<div class="departments index content col-95 mt-5 mx-auto">
    <?= $this->Html->link(__('Back to Department view'), [
        'prefix' => 'Admin',
        'action' => 'view',
        $employees->first()->dept_no
    ], [
        'class' => 'button btn-blue float-right'
    ]) ?>
    <h3><?= __('Related Employees') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
            <tr>
                <th><?= $this->Paginator->sort('emp_no') ?></th>
                <th><?= $this->Paginator->sort('birth_date') ?></th>
                <th><?= $this->Paginator->sort('first_name') ?></th>
                <th><?= $this->Paginator->sort('last_name') ?></th>
                <th><?= $this->Paginator->sort('gender') ?></th>
                <th><?= $this->Paginator->sort('hire_date') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($employees as $employee) : ?>
                <tr>

                    <td><?= h($employee->em['emp_no']) ?></td>
                    <td><?= h($employee->em['birth_date']) ?></td>
                    <td><?= h($employee->em['first_name']) ?></td>
                    <td><?= h($employee->em['last_name']) ?></td>
                    <td><?= h($employee->em['gender']) ?></td>
                    <td><?= h($employee->em['hire_date']) ?></td>

                    <td class="actions">
                        <!-- Button view /admin/employees/view/:emp_no -->
                        <?= $this->Html->link(__('View'), [
                            'prefix' => 'Admin',
                            'controller' => 'Employees',
                            'action' => 'view',
                            $employee->em['emp_no']
                        ]) ?>

                        <!-- Button edit /admin/employees/edit/:emp_no -->
                        <?= $this->Html->link(__('Edit'), [
                            'prefix' => 'Admin',
                            'controller' => 'Employees',
                            'action' => 'edit',
                            $employee->em['emp_no']
                        ]) ?>

                        <!-- Button delete /admin/employees/delete/:emp_no -->
                        <?= $this->Form->postLink(__('Delete'), [
                            'prefix' => 'Admin',
                            'controller' => 'Employees',
                            'action' => 'delete', $employee->em['emp_no']
                        ], [
                            'confirm' => __('Are you sure you want to delete # {0}?', $employee->em['emp_no'])
                        ]) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
