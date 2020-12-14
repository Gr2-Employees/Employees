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
            <?= $this->Html->link(__('Edit Department'), ['action' => 'edit', $department->dept_no], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Department'), ['action' => 'delete', $department->dept_no], ['confirm' => __('Are you sure you want to delete # {0}?', $department->dept_no), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Departments'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Department'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>

    <div class="column-80">
        <div class="departments view content">
            <h1><?= __('Department of') . ' ' . h($department->dept_name) ?></h1>

            <!-- Informations sur le manager -->
            <div id="manager-container">
                <div id="manager-info">
                    <h4><?= __('Manager') ?></h4>
                    <h5><?= h($department->manager_name) ?></h5>
                </div>

                <div id="manager-pic">
                    <?= $this->Html->image('/img/' . $department->picture, [
                        'alt' => 'Manager du département ' . $department->dept_name . '.',
                        'width' => '300px',
                        'height' => '300px',
                        'class' => 'picture'
                    ]) ?>
                </div>
            </div>
            <table>
                <tr>
                    <th><?= __('Number') ?></th>
                    <td><?= h(strtoupper($department->dept_no)) ?></td>
                </tr>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($department->dept_name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Description') ?></th>
                    <td><?= h($department->description) ?></td>
                </tr>

                <!-- Nombre total d'employés-->
                <tr>
                    <th><?= __('Number of employees') ?></th>
                    <td><?= h($department->nbEmpl) . ' ' . __('employees') ?></td>
                </tr>
                <!-- Nombre de postes vacants -->
                <tr>
                    <th><?= __('Number of available positions') ?></th>
                    <td>
                        <?= h($department->nbVacants) . ' ' . __('positions') ?>
                        <?= $this->Html->link(
                            'Apply for a position',
                            [
                                'controller' => 'Vacancies',
                                'action' => 'showOffers',
                                '?' => ['dept_no' => $department->dept_no]
                            ],
                            [
                                'class' => 'btn btn-success',
                                'style' => 'float: right',
                                'target' => '_blank'
                            ]) ?>
                    </td>
                </tr>
                <!-- Fichier ROI du département (fichier unique) -->
                <tr>
                    <th><?= __('Internal regulations (ROI)') ?></th>
                    <td><?= $this->Html->link(__('Internal-Regulations.pdf'), '/files/ROI-departement.pdf', [
                            'target' => '_blank'
                        ]) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
