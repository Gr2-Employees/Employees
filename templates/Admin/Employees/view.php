<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Employee $employee
 */
?>
<div class="row p-5">
    <aside class="col-15">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Employee'), ['action' => 'edit', $employee->emp_no], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Employee'), ['action' => 'delete', $employee->emp_no], ['confirm' => __('Are you sure you want to delete # {0}?', $employee->emp_no), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Employees'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Employee'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="col-80 m-auto">
        <div class="employees view content">
            <h3><?= h($employee->emp_no) ?></h3>
            <table>
                <tr>
                    <th><?= __('First Name') ?></th>
                    <td><?= h($employee->first_name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Last Name') ?></th>
                    <td><?= h($employee->last_name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Gender') ?></th>
                    <td><?= h($employee->gender) ?></td>
                </tr>
                <tr>
                    <th><?= __('Picture') ?></th>
                    <td><?= h($employee->picture) ?></td>
                </tr>
                <tr>
                    <th><?= __('Email') ?></th>
                    <td><?= h($employee->email) ?></td>
                </tr>
                <tr>
                    <th><?= __('Emp No') ?></th>
                    <td><?= $this->Number->format($employee->emp_no) ?></td>
                </tr>
                <tr>
                    <th><?= __('Birth Date') ?></th>
                    <td><?= h($employee->birth_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Hire Date') ?></th>
                    <td><?= h($employee->hire_date) ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Related Salaries') ?></h4>
                <?php if (!empty($employee->salaries)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Emp No') ?></th>
                            <th><?= __('Salary') ?></th>
                            <th><?= __('From Date') ?></th>
                            <th><?= __('To Date') ?></th>
                        </tr>
                        <?php foreach ($employee->salaries as $salaries) : ?>
                        <tr>
                            <td><?= h($salaries->emp_no) ?></td>
                            <td><?= h($salaries->salary) ?></td>
                            <td><?= h($salaries->from_date) ?></td>
                            <td><?= h($salaries->to_date) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related Employee Title') ?></h4>
                <?php if (!empty($employee->employee_title)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Emp No') ?></th>
                            <th><?= __('Title No') ?></th>
                            <th><?= __('From Date') ?></th>
                            <th><?= __('To Date') ?></th>
                            <th><?= __('Description') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($employee->employee_title as $employeeTitle) : ?>
                        <tr>
                            <td><?= h($employeeTitle->emp_no) ?></td>
                            <td><?= h($employeeTitle->title_no) ?></td>
                            <td><?= h($employeeTitle->from_date) ?></td>
                            <td><?= h($employeeTitle->to_date) ?></td>
                            <td><?= h($employeeTitle->description) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'employee_title', 'action' => 'view', $employeeTitle->emp_no]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'employee_title', 'action' => 'edit', $employeeTitle->emp_no]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'employee_title', 'action' => 'delete', $employeeTitle->emp_no], ['confirm' => __('Are you sure you want to delete # {0}?', $employeeTitle->emp_no)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
