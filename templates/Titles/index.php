<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Title[]|\Cake\Collection\CollectionInterface $titles
 */
?>
<div class="titles index content">
    <?= $this->Html->link(__('New Title'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Titles') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('emp_no') ?></th>
                    <th><?= $this->Paginator->sort('title') ?></th>
                    <th><?= $this->Paginator->sort('from_date') ?></th>
                    <th><?= $this->Paginator->sort('to_date') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($titles as $title): ?>
                <tr>
                    <td><?= $this->Number->format($title->emp_no) ?></td>
                    <td><?= h($title->title) ?></td>
                    <td><?= h($title->from_date) ?></td>
                    <td><?= h($title->to_date) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $title->emp_no]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $title->emp_no]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $title->emp_no], ['confirm' => __('Are you sure you want to delete # {0}?', $title->emp_no)]) ?>
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
