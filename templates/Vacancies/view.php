<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Vacancy $vacancy
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Vacancy'), ['action' => 'edit', $vacancy->dept_no], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Vacancy'), ['action' => 'delete', $vacancy->dept_no], ['confirm' => __('Are you sure you want to delete # {0}?', $vacancy->dept_no), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Vacancies'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Vacancy'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="vacancies view content">
            <h3><?= h($vacancy->dept_no) ?></h3>
            <table>
                <tr>
                    <th><?= __('Dept No') ?></th>
                    <td><?= h($vacancy->dept_no) ?></td>
                </tr>
                <tr>
                    <th><?= __('Title No') ?></th>
                    <td><?= $this->Number->format($vacancy->title_no) ?></td>
                </tr>
                <tr>
                    <th><?= __('Quantity') ?></th>
                    <td><?= $this->Number->format($vacancy->quantity) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
