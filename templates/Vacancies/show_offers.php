<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Vacancy[]|\Cake\Collection\CollectionInterface $vacancies
 */
?>
<div class="vacancies index content">
    <div id="TitlePage">
        <?php if (isset($vacancyName) && !is_null($vacancies)) { ?>
        <h4> <?= __('Vacant position(s) in department of ') . h($vacancyName) ?></h4>

    </div>

    <div id="tableVacancies">
        <table>
            <th>Title</th>
            <th>Amount</th>
            <th>Action</th>

            <?php foreach ($vacancies as $vacancy) { ?>
                <tr>
                    <td><?= $vacancy->title ?></td>
                    <td><?= $vacancy->amount ?></td>
                    <td>
                        <?php if ($vacancy->amount !== '0') {
                            echo $this->Html->link(__('Hire me !'), [
                                'action' => 'applyOffer',
                                '?' => [
                                    'title' => $vacancy->title_no
                                ],
                            ]);
                        } ?>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
    <?php } else { ?>
        <h4>Page Hire</h4>
        <?= $this->Flash->render() ?>
    <?php } ?>
</div>
