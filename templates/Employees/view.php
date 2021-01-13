<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Employee $employee
 */
?>
<div class="row principal-row row-styled-background">
    <div class="col-8 m-auto">
        <div class="employees view content position-relative p-5">
            <h3><?= h($employee->emp_no) ?></h3>
            <?= $this->Html->link(__('<i></i>'), 'employees/', [
                "class" => "far fa-2x fa-window-close position-absolute",
                "style" => "right:30px;top:25px;",
                "escape" => false
            ]) ?>
            <div class="row">
                <!-- Employee's main info -->
                <div class="col-6">
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
                    </table>
                </div>

                <!-- Employee's picture-->
                <div class="col-6">
                    <?php if (!is_null($employee->picture)) { ?>
                        <?= $this->Html->image('uploads/emp_pictures/' . $employee->picture, [
                            'alt' => 'Employee picture ' . $employee->dept_name,
                            'class' => 'manager-picture float-right'
                        ]) ?>
                    <?php } else { ?>
                        <?= $this->Html->image('/img/noUserPic.jpg', [
                            'class' => 'manager-picture float-right'
                        ]) ?>
                    <?php } ?>
                </div>

                <div class="col">
                    <table>
                        <!-- Employee's hire date -->
                        <tr>
                            <th><?= __('Hire Date') ?></th>
                            <td><?= h($employee->hire_date) ?></td>
                        </tr>

                        <!-- Employee's function (role) -->

                        <tr>
                            <th><?= __('Function') ?></th>
                            <td><?= h($employee->fonction) ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
