<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Title[]|\Cake\Collection\CollectionInterface $titles
 */
?>

<div class="titles index content">
    <?= $this->Html->link(__('New Title'), [
        'action' => 'add'
    ], [
        'class' => 'button float-right'
    ]) ?>
    <h3><?= __('Titles') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('title_no') ?></th>
                    <th><?= $this->Paginator->sort('title') ?></th>
                    <th><?= $this->Paginator->sort('description') ?></th>
                    <th><?= $this->Paginator->sort('Amount of Employees') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($titles as $title): ?>
                <tr>
                    <td><?= $this->Number->format($title->title_no) ?></td>
                    <td><?= h($title->title) ?></td>
                    <td><?= h($title->description) ?></td>
                    <td><?= $this->Number->format($title->nbEmpl) ?></td>
                    <td class="actions">
                        <!-- Button Delete Title -->
                        <?php if ($title->nbEmpl === 0) {?>
                        <?= $this->Form->postLink(__('Delete'), [
                            'action' => 'delete',
                            $title->title_no
                        ], [
                            'confirm' => __('Are you sure you want to delete # {0}?', $title->emp_no),
                            'class' => 'button btn-danger',
                            'style' => 'color: white'
                        ]) ?>
                        <?php } else {
                            echo __('No action available');
                        } ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>
