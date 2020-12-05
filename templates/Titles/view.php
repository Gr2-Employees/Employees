<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Title $title
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Title'), ['action' => 'edit', $title->emp_no], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Title'), ['action' => 'delete', $title->emp_no], ['confirm' => __('Are you sure you want to delete # {0}?', $title->emp_no), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Titles'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Title'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="titles view content">
            <h3><?= h($title->title) ?></h3>
            <table>
                <tr>
                    <th><?= __('Title') ?></th>
                    <td><?= h($title->title) ?></td>
                </tr>
                <tr>
                    <th><?= __('Emp No') ?></th>
                    <td><?= $this->Number->format($title->emp_no) ?></td>
                </tr>
                <tr>
                    <th><?= __('From Date') ?></th>
                    <td><?= h($title->from_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('To Date') ?></th>
                    <td><?= h($title->to_date) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
