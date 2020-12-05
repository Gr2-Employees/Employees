<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Employee $employee
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Employee'), ['action' => 'edit', $employee->emp_no], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Employee'), ['action' => 'delete', $employee->emp_no], ['confirm' => __('Are you sure you want to delete # {0}?', $employee->emp_no), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Employees'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Employee'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
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
                <tr>
                    <th><?= __('Function') ?></th>
                    <td><?= h($employee->fonction) ?></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <ul>
                        <?php foreach($employee->salaries as $salary) : ?>
                            <li><?= $this->Number->currency($salary->salary,"EUR",[
                                'locale' => 'FR_fr'
                            ]) ?></li>
                        <?php endforeach; ?>
                        </ul>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
