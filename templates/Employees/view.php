<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Employee $employee
 */
?>
<div class="row principal-row">
    <div class="col-8">
        <div class="employees view content position-relative">
            <h3><?= h($employee->emp_no) ?></h3>
            <?= $this->Html->link(__('<i></i>'), 'employees/',
                [
                    "class" => "far fa-2x fa-window-close position-absolute",
                    "style" => "right:30px;top:25px;",
                    "escape" => false
                ]) ?>
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
                    <th><?= __('Salaries') ?></th>
                    <td colspan="2">
                        <ul>
                            <?php foreach ($employee->salaries as $salary) : ?>
                                <li><?= $this->Number->currency($salary->salary, "EUR", [
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
