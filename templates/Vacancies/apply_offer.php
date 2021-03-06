<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Vacancy[]|\Cake\Collection\CollectionInterface $vacancies
 */
?>
<div class="row-styled-background pt-5">
    <div class="vacancies index content col-80 mx-auto">
        <div id="TitlePage">
            <h2> <?= __('Hire me') ?></h2>
        </div>
        <?php if ($showForm) { ?>
            <!-- Hire-me Form -->
            <?= $this->Form->create(null, [
                'url' => [
                    'action' => 'applyOffer'
                ],
                'type' => 'file'
            ]) ?>

            <!-- Hidden dept_no field-->
            <?= $this->Form->hidden('dept_no', [
                'value' => $dept_no
            ]) ?>

            <!-- Hidden title_no field-->
            <?= $this->Form->hidden('title_no', [
                'value' => $title_no
            ]) ?>

            <!-- Surname Input -->
            <?= $this->Form->control('surname', [
                'id' => 'surnameField',
                'type' => 'text',
                'placeholder' => __('Enter your surname')
            ]) ?>

            <!-- Lastname Input -->
            <?= $this->Form->control('lastname', [
                'id' => 'lastnameField',
                'type' => 'text',
                'placeholder' => __('Enter your lastname')
            ]) ?>

            <!-- Email Input -->
            <?= $this->Form->control('email', [
                'type' => 'email',
                'placeholder' => 'Your Email'
            ]) ?>

            <!-- Birthdate Field - TODO:Change format -->
            <?= $this->Form->control('birthdate', [
                'type' => 'date',
            ]) ?>

            <!-- Text Area -->
            <?= $this->Form->control('motivations', [
                'type' => 'textarea',
                'rows' => '4',
                'cols' => '10',
                'placeholder' => 'Your motivations'
            ]) ?>

            <!-- File Input Word/PDF -->
            <?= $this->Form->control('file', [
                'type' => 'file'
            ]) ?>

            <!-- Submit button -->
            <?= $this->Form->button('Submit', ["class" => "btn-blue"]) ?>

            <!-- End Form -->
            <?= $this->Form->end() ?>

        <?php } else { ?>
            <!-- Link back to homepage -->
            <div id="mailOutput">
                <?= $this->Html->link(__('Back Home'), '/', [
                    'controller' => 'Pages',
                    'action' => 'display'
                ]) ?>
            </div>
        <?php } ?>
    </div>
</div>
