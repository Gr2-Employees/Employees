<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Demand $demand
 */
?>
<div class="row row-styled-background">
    <div class="column-responsive column-75 m-auto">
        <div class="demands form content position-relative">
            <?= $this->Html->link(__('List Demands'), ['action' => 'index'], ['class' => 'button btn-blue position-absolute','style'=> 'right:20px']) ?>
            <?= $this->Form->create($demand) ?>
            <fieldset>
                <legend><?= __('Add Demand') ?></legend>
                <?php
                    echo $this->Form->select('type',[
                        'type' => [
                            'raise' => 'Raise',
                            'Department change' => 'Department change'
                        ]
                    ]);
                    echo $this->Form->control('about',['placeholder' => 'Talk about it in more detail']);
                    echo $this->Form->control('amount',[
                        'label' => 'Please complete this field if you want a raise.',
                        'placeholder' => 'Amount'
                    ]);

                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit'),['class' => 'btn-blue']) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
