<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Demand[]|\Cake\Collection\CollectionInterface $demands
 */
?>
<div class="demands index content">
    <?= $this->Html->link(__('New Demand'), ['action' => 'add'], ['class' => 'button btn-blue float-right']) ?>
    <h3><?= __('Department change demands') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
            <tr>
                <th><?= __('id') ?></th>
                <th><?= __('emp_no') ?></th>
                <th><?= __('type') ?></th>
                <th><?= __('about') ?></th>
                <th><?= __('status') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($demandsDepartment as $demand) { ?>
                <tr>
                    <td><?= $this->Number->format($demand->id) ?></td>
                    <td><?= $this->Number->format($demand->emp_no) ?></td>
                    <td><?= h($demand->type) ?></td>
                    <td><?= h($demand->about) ?></td>
                    <td><?= h($demand->status) ?></td>
                    <td class="actions">
                        <?php if ($this->Identity->get('role') !== 'admin') { ?>
                            <?php if ($demand->status !== 'approved') { ?>
                                <!-- Link to Approve demand -->
                                <?= $this->Form->postLink(__('Approve'), [
                                    'controller' => 'Demands',
                                    'action' => 'approve', $demand->id
                                ], [
                                    'confirm' => __('Are you sure you want to approve # {0}?',
                                        $demand->id),
                                    'class' => 'button',
                                    'style' => 'color:white;background-color:green;border-color:green'

                                ]) ?>

                                <!-- Link to Decline demand -->
                                <?= $this->Form->postLink(__('Decline'), [
                                    'controller' => 'Demands',
                                    'action' => 'decline', $demand->id
                                ], [
                                    'confirm' => __('Are you sure you want to decline # {0}?',
                                        $demand->id),
                                    'class' => 'button',
                                    'style' => 'color:white;'
                                ]) ?>
                            <?php } ?>
                        <?php } else {
                            if ($this->Identity->get('role') === 'admin') {
                                // Link to Delete demand
                                echo $this->Form->postLink(__('Delete'), [
                                    'controller' => 'Demands',
                                    'action' => 'delete',
                                    $demand->id
                                ], [
                                    'confirm' => __('Are you sure you want to delete # {0}?',
                                        $demand->id),
                                    'class' => 'button',
                                    'style' => 'color:white'
                                ]);
                            }
                        } ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>

    <div class="table-responsive mt-4">
        <h3><?= __('Raise salary demands') ?></h3>
        <!-- Table raise pour le comptable-->
        <?php if ($this->Identity->get('role') === 'comptable' || $this->Identity->get('role') === 'admin') { ?>
            <table>
                <thead>
                <tr>
                    <th><?= __('id') ?></th>
                    <th><?= __('emp_no') ?></th>
                    <th><?= __('salary') ?></th>
                    <th><?= __('type') ?></th>
                    <th><?= __('about') ?></th>
                    <th><?= __('status') ?></th>
                    <th><?= __('amount') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($demandsRaise as $demand) { ?>
                    <tr>
                        <td><?= $this->Number->format($demand->id) ?></td>
                        <td><?= $this->Number->format($demand->emp_no) ?></td>
                        <td><?= $this->Number->format($demand->s['salary'], ['after' => '$']) ?></td>
                        <td><?= h($demand->type) ?></td>
                        <td><?= h($demand->about) ?></td>
                        <td><?= h($demand->status) ?></td>
                        <?php if ($demand->type === 'raise') { ?>
                            <td><?= $this->Number->toPercentage(h($demand->amount)) ?></td>
                        <?php } else { ?>
                            <td><?= __('NULL') ?></td>
                        <?php } ?>
                        <td class="actions">
                            <?php if ($demand->status === 'in progress' && $this->Identity->get('role') !== 'admin') { ?>

                                <!-- Link to Approve raise demand -->
                                <?= $this->Form->postLink(__('Approve'), [
                                    'controller' => 'Demands',
                                    'action' => 'approve', $demand->id,
                                    '?' => [
                                        'type' => 'raise'
                                    ]
                                ], [
                                    'confirm' => __('Are you sure you want to approve # {0}?',
                                        $demand->id),
                                    'class' => 'button',
                                    'style' => 'color:white;background-color:green;border-color:green'

                                ]) ?>

                                <!-- Link to Decline raise demand -->
                                <?= $this->Form->postLink(__('Decline'), [
                                    'controller' => 'Demands',
                                    'action' => 'decline', $demand->id
                                ], [
                                    'confirm' => __('Are you sure you want to decline # {0}?',
                                        $demand->id),
                                    'class' => 'button',
                                    'style' => 'color:white;'
                                ]) ?>

                            <?php } else {
                                if ($this->Identity->get('role') === 'admin') {
                                    // Link to Delete raise demand
                                    echo $this->Form->postLink(__('Delete'), [
                                        'controller' => 'Demands',
                                        'action' => 'delete',
                                        $demand->id
                                    ], [
                                        'confirm' => __('Are you sure you want to delete # {0}?',
                                            $demand->id),
                                        'class' => 'button',
                                        'style' => 'color:white'
                                    ]);
                                }
                            } ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        <?php } ?>
    </div>
</div>
