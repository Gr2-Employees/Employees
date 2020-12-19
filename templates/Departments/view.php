<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Department $department
 */
?>
<div class="row principal-row">
    <aside class="col-aside">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Departments'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>

    <div class="col-department">
        <div class="departments view content">
            <h1><?= __('Department of') . ' ' . h($department->dept_name) ?></h1>

            <!-- Informations sur le manager -->
            <div id="manager-container" class="row">
                <div id="manager-info" class="col-6">
                    <h3><?= __('Manager') ?></h3>
                    <h4><?= h($department->manager_name) ?></h4>
                    <h3><?= __('Department number') ?></h3>
                    <h4><?= h(strtoupper($department->dept_no)) ?></h4>

                </div>

                <div id="manager-pic" class="col-6">
                    <?= $this->Html->image('/img/' . $department->picture, [
                        'alt' => 'Manager du département ' . $department->dept_name . '.',
                        'class' => 'manager-picture'
                    ]) ?>
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
            </table>
        </div>
    </div>
</div>
