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
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $title->emp_no],
                ['confirm' => __('Are you sure you want to delete # {0}?', $title->emp_no), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Titles'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="titles form content">
            <?= $this->Form->create($title) ?>
            <fieldset>
                <legend><?= __('Edit Title') ?></legend>
                <?php
                    echo $this->Form->control('to_date', ['empty' => true]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
