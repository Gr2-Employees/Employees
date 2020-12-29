<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Demand $demand
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Demands'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="demands form content">
            <?= $this->Form->create($demand) ?>
            <fieldset>
                <legend><?= __('Add Demand') ?></legend>
                <?php
                    echo $this->Form->select('type',[
                        'raise' => 'Raise',
                        'Department_change' => 'Department change'
                    ]);
                    echo $this->Form->control('about');
                    echo $this->Form->control('amount',[
                        'label' => 'Please complete this field if you want a raise.',
                        'placeholder' => 'Amount'
                    ]);

                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
