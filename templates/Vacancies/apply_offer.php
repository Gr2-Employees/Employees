<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Vacancy[]|\Cake\Collection\CollectionInterface $vacancies
 */
?>
<div class="vacancies index content">
    <div id="TitlePage">
        <h2> <?= __('Hire me') ?></h2>
    </div>

    <?php if (!$isFormSent) { ?>
        <!-- Hire me Form -->
        <?= $this->Form->create(null, [
            'url' => [
                'action' => 'applyOffer'
            ]
        ]) ?>

        <!-- Surname -->
        <?= $this->Form->control('Surname', [
            'id' => 'surnameField',
            'type' => 'text',
            'placeholder' => __('Enter your surname')
        ]) ?>

        <!-- Lastname -->
        <?= $this->Form->control('Surname', [
            'id' => 'lastnameField',
            'type' => 'text',
            'placeholder' => __('Enter your lastname')
        ]) ?>

        <!-- Email -->
        <?= $this->Form->control('Email', [
            'type' => 'email'
        ]) ?>

        <!-- Submit button -->
        <?= $this->Form->button('Submit') ?>

        <!-- End Form -->
        <?= $this->Form->end() ?>
    <?php } ?>
</div>
