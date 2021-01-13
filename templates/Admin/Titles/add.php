<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Vacancy $title
 */
?>
<div class="row row-styled-background">
    <div class="column-responsive column-60 m-auto">
        <div class="departments form content position-relative">
            <?= $this->Form->create($title) ?>
            <fieldset>
                <legend><?= __('Add Title') ?></legend>
                <!-- Button admin/dept/index -->
                <?= $this->Html->link(__('List Titles'), [
                    'prefix' => 'Admin',
                    'action' => 'index'
                ], [
                    'class' => 'button btn-blue position-absolute', 'style' => "top: 40px;right: 40px"
                ]) ?>
                <?php
                echo $this->Form->control('title', [
                    'type' => 'text',
                    'required' => 'true'
                ]);
                echo $this->Form->control('description', ['required' => 'true']);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit'), ['class' => 'btn-blue']) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

