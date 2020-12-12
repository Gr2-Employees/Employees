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
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $vacancy->dept_no],
                ['confirm' => __('Are you sure you want to delete # {0}?', $vacancy->dept_no), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Vacancies'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="vacancies form content">
            <?= $this->Form->create($vacancy) ?>
            <fieldset>
                <legend><?= __('Edit Vacancy') ?></legend>
                <?php
                    echo $this->Form->control('quantity');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
