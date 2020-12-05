<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Salary $salary
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Salary'), ['action' => 'edit', $salary->emp_no], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Salary'), ['action' => 'delete', $salary->emp_no], ['confirm' => __('Are you sure you want to delete # {0}?', $salary->emp_no), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Salaries'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Salary'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="salaries view content">
            <h3><?= h($salary->emp_no) ?></h3>
            <table>
                <tr>
                    <th><?= __('Emp No') ?></th>
                    <td><?= $this->Number->format($salary->emp_no) ?></td>
                </tr>
                <tr>
                    <th><?= __('Salary') ?></th>
                    <td><?= $this->Number->format($salary->salary) ?></td>
                </tr>
                <tr>
                    <th><?= __('From Date') ?></th>
                    <td><?= h($salary->from_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('To Date') ?></th>
                    <td><?= h($salary->to_date) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
