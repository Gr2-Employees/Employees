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
        <?= $this->Form->control('surname', [
            'id' => 'surnameField',
            'type' => 'text',
            'placeholder' => __('Enter your surname')
        ]) ?>

        <!-- Lastname -->
        <?= $this->Form->control('lastname', [
            'id' => 'lastnameField',
            'type' => 'text',
            'placeholder' => __('Enter your lastname')
        ]) ?>

        <!-- Email -->
        <?= $this->Form->control('email', [
            'type' => 'email',
            'placeholder' => 'Your Email'
        ]) ?>

        <!-- Text Area -->
        <?= $this->Form->control('motivations', [
            'type' => 'textarea',
            'rows' => '4',
            'cols' => '10',
            'placeholder' => 'Your motivations'
        ]) ?>

        <!-- File Input Word/PDF -->
        <?= $this->Form->control('c-v', [
            'type' => 'file'
        ]) ?>
        <!-- Submit button -->
        <?= $this->Form->button('Submit') ?>

        <!-- End Form -->
        <?= $this->Form->end() ?>
    <?php } ?>

    <!-- TODO: Add Elements to tell user the form has been sent -->
</div>
