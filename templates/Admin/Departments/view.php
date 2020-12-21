<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Department $department
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Department'), ['action' => 'edit', $department->dept_no], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Department'), ['action' => 'delete', $department->dept_no], ['confirm' => __('Are you sure you want to delete # {0}?', $department->dept_no), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Departments'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Department'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="departments view content">
            <div style="position: relative" class="row">
                <h1><?= __('Department of') . ' ' . h($department->dept_name) ?></h1>

                <!-- Button close view -->
                <?= $this->Html->link(__('<i></i>'), '/departments', [
                    "class" => "far fa-2x fa-window-close",
                    "style" => "right:0;position:absolute",
                    "escape" => false
                ]) ?>
            </div>

            <!-- Informations sur le manager -->
            <div id="manager-container" class="row">
                <div id="manager-info" class="col-6">
                    <!-- Manager Name -->
                    <h3><?= __('Manager') ?></h3>
                    <h4><?= h($department->manager_name) ?></h4>

                    <!-- Department Number-->
                    <h3><?= __('Department number') ?></h3>
                    <h4><?= h(strtoupper($department->dept_no)) ?></h4>
                </div>

                <!-- Manager Picture -->
                <div id="manager-pic" class="col-6">
                    <?= $this->Html->image('/img/' . $department->picture, [
                        'alt' => 'Manager du département ' . $department->dept_name . '.',
                        'class' => 'manager-picture'
                    ]) ?>
                </div>

            </div>
            <table>
                <!-- Department's description-->
                <tr>
                    <th><?= __('Description') ?></th>
                    <td><?= h($department->description) ?></td>
                </tr>

                <!-- Nombre total d'employés-->
                <tr>
                    <th><?= __('Number of employees') ?></th>
                    <td><?= h($department->nbEmpl) . ' ' . __('employees') ?></td>
                </tr>

                <!-- Fichier ROI du département (fichier unique) -->
                <tr>
                    <th><?= __('Internal regulations (ROI)') ?></th>
                    <td><?= $this->Html->link(__('Internal-Regulations.pdf'), '/files/ROI-departement.pdf', [
                            'target' => '_blank'
                        ]) ?></td>
                </tr>

                <!-- Nombre de postes vacants -->
                <tr>
                    <th><?php if ($department->nbVacants <= 1) {
                            echo h($department->nbVacants) . ' ' . __('position available');
                        } else {
                            echo h($department->nbVacants) . ' ' . __('positions available');
                        } ?>
                    </th>

                    <td><?php if (h($department->nbVacants) !== 0) { ?>
                            <?= $this->Html->link(
                                'See vacant positions',
                                [
                                    'controller' => '../Vacancies',
                                    'action' => 'show_offers',
                                    '?' => ['dept_no' => $department->dept_no]
                                ],
                                [
                                    'class' => 'btn btn-submit',
                                    'target' => '_blank'
                                ]) ?>
                        <?php } else {
                            echo __('No position available');
                        } ?>
                    </td>
                </tr>

                <!-- Related Employees-->
                <tr>
                    <th><?= __('Related Employees') ?></th>
                    <td>
                        <?= $this->Html->link(__('Display the employees of ' . $department->dept_name), [
                            'action' => 'show_emp',
                            $department->dept_no
                        ], [
                            'class' => 'btn btn-submit'
                        ]) ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Statistics & informations -->
    <div style="width:100%;">
        <h2><?= __('Statistics & informations') ?></h2>
        <table>
            <!-- Manager Name + time spent as manager -->
            <tr>
                <th><?= __('Manager') ?></th>
                <td><?= $department->manager_name . __(', since ') . $department->since ?></td>
            </tr>

            <!-- Number of employees-->
            <tr>
                <th><?= __('Number of employees') ?></th>
                <td><?= __('There are ') . $department->nbEmpl . ' ' . __('employees in this department.') ?></td>
            </tr>

            <!-- Employee's average salary (without Manager's salary) -->
            <tr>
                <th><?= __('Employee\'s Average Salary') ?></th>
                <td><?= $this->Number->currency($department->averageSalary, 'USD') ?></td>
            </tr>
        </table>
    </div>
</div>
