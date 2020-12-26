<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Department $department
 */
?>
<div class="row principal-row">
    <?php if ($this->Identity->isLoggedIn() && $this->Identity->get('role') == 'admin') { ?>
        <aside class="col-aside">
            <div class="side-nav">
                <h4 class="heading"><?= __('Actions') ?></h4>
                <?php if ($this->Identity->isLoggedIn() && ($this->Identity->get('role') === 'admin')) : ?>
                    <!-- Button Edit Department-->
                    <?= $this->Html->link(__('Edit Department'), [
                        'prefix' => 'Admin',
                        'controller' => 'Departments',
                        'action' => 'edit',
                        $department->dept_no
                    ], ['class' => 'side-nav-item']) ?>

                    <!-- Button Delete Department-->
                    <?= $this->Form->postLink(__('Delete Department'), [
                        'prefix' => 'Admin',
                        'controller' => 'Departments',
                        'action' => 'delete',
                        $department->dept_no
                    ], [
                        'confirm' => __('Are you sure you want to delete # {0}?',
                            $department->dept_no),
                        'class' => 'side-nav-item']) ?>

                    <!-- Button Add Department-->
                    <?= $this->Html->link(__('New Department'), [
                        'prefix' => 'Admin',
                        'controller' => 'Departments',
                        'action' => 'add'], [
                        'class' => 'side-nav-item'
                    ]) ?>

                <?php endif; ?>

                <?= $this->Html->link(__('List Departments'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            </div>
        </aside>
    <?php } ?>
    <div class="column-80">
        <div class="departments view content">
            <div class="row position-relative">
                <h1><?= __('Department of') . ' ' . h($department->dept_name) ?></h1>
                <?= $this->Html->link(__('<i></i>'), '/departments',
                    [
                        "class" => "far fa-2x fa-window-close position-absolute",
                        "style" => "right:0;",
                        "escape" => false
                    ]) ?>
            </div>

            <!-- Informations sur le manager -->
            <div id="manager-container" class="row">
                <div id="manager-info" class="col-6">
                    <!-- Manager Name -->
                    <h3><?= __('Manager') ?></h3>
                    <?php if (!is_null($department->manager_name)) { ?>
                        <h4><?= h($department->manager_name) ?></h4>
                    <?php } else { ?>
                        <h4><?= __('Currently no manager in this department') ?></h4>
                    <?php } ?>

                    <!-- Department Number-->
                    <h3><?= __('Department number') ?></h3>
                    <h4><?= h(strtoupper($department->dept_no)) ?></h4>
                </div>

                <div id="manager-pic" class="col-6">
                    <?php if (!is_null($department->picture)) { ?>
                        <?= $this->Html->image('/img/' . $department->picture, [
                            'alt' => 'Manager du département ' . $department->dept_name,
                            'class' => 'manager-picture'
                        ]) ?>
                    <?php } else { ?>
                        <?= $this->Html->image('/img/noUserPic.jpg', [
                            'alt' => 'Manager du département ' . $department->dept_name,
                            'class' => 'manager-picture'
                        ]) ?>
                    <?php } ?>
                </div>

            </div>
            <table>
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

                                'Apply for a position',
                                [
                                    'controller' => 'Vacancies',
                                    'action' => 'showOffers',
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
                <!-- Demandes d'affectation -->
                <?php if($this->Identity->get('role') === 'member') { ?>
                <tr>
                    <th><?= __('Demande d\'affectation') ?></th>
                    <td><?= $this->Html->link(__('Request'), [
                            'controller' => 'Demands',
                            'action' => 'add',
                            'target' => '_blank'
                        ]) ?></td>
                </tr>
                <?php } ?>
                <!-- Demandes d'affectation -->
                <?php if($this->Identity->get('role') === 'comptable' || $this->Identity->get('role') === 'manager') { ?>
                    <tr>
                        <th><?= __('View requests') ?></th>
                        <td><?= $this->Html->link(__('View requests'), [
                                'controller' => 'Demands',
                                'action' => 'index',
                                'target' => '_blank'
                            ]) ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>
