<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Partner $partner
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $partner->title],
                ['confirm' => __('Are you sure you want to delete # {0}?', $partner->title), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Partners'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="partners form content">
            <?= $this->Form->create($partner) ?>
            <fieldset>
                <legend><?= __('Edit Partner') ?></legend>
                <?php
                    echo $this->Form->control('url');
                    echo $this->Form->control('logo');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
