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
            <?= $this->Html->link(__('Edit Partner'), ['action' => 'edit', $partner->title], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Partner'), ['action' => 'delete', $partner->title], ['confirm' => __('Are you sure you want to delete # {0}?', $partner->title), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Partners'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Partner'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="partners view content">
            <h3><?= h($partner->title) ?></h3>
            <table>
                <tr>
                    <th><?= __('Title') ?></th>
                    <td><?= h($partner->title) ?></td>
                </tr>
                <tr>
                    <th><?= __('Url') ?></th>
                    <td><?= h($partner->url) ?></td>
                </tr>
                <tr>
                    <th><?= __('Logo') ?></th>
                    <td><?= h($partner->logo) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
