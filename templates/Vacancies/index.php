<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Vacancy[]|\Cake\Collection\CollectionInterface $vacancies
 */
?>
<div class="vacancies index content">
    <?= $this->Html->link(__('New Vacancy'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Vacancies') ?></h3>
    <div id="vacanciesList">
        <h6>Vacancies list</h6>
        <!-- Show every vacancy -->
    </div>
</div>
