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
            <h1><?= h(strtoupper($department->dept_no)) ?></h1>
            <h4>Manager</h4>
            <div class="manager-pic">
                <?= $this->Html->image('/img/' . $department->picture, [
                    'alt' => 'Manager du département ' . $department->dept_name . '.',
                    'width' => '250px',
                    'height' => '250px',
                    'class' => 'manager-pic'
                ]) ?>
            </div>
            <div class="manager-info">
                <?= h($department->manager_name) ?>
            </div>
            <table>
                <tr>
                    <th><?= __('Department Number') ?></th>
                    <td><?= h(strtoupper($department->dept_no)) ?></td>
                </tr>
                <tr>
                    <th><?= __('Department Name') ?></th>
                    <td><?= h($department->dept_name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Department Description') ?></th>
                    <td><?= h($department->description) ?></td>
                </tr>

                <!-- Nombre total d'employés-->
                <tr>
                    <th><?= __('Number of employees') ?></th>
                    <td><?= h($department->nbEmpl) . ' ' . __('employees') ?></td>
                </tr>
                <!-- Nombre de postes vacants -->
                <tr>
                    <th><?= __('Number of empty positions') ?></th>
                    <td>
                        <?= h($department->nbVacants) . ' ' . __('employees') ?>
                        <!-- TODO: LIEN POUR POSTULER -> VERS VUE ?-->
                        <?= $this->Html->link(
                            'Postuler',
                            '/',
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
                    <td><?= $this->Html->link( __('Internal-Regulations.pdf'), '/files/ROI-departement.pdf', [
                        'target' => '_blank'
                    ]) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
