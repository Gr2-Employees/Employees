<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Vacancy[]|\Cake\Collection\CollectionInterface $vacancies
 */
?>
<div class="vacancies index content">
    <div id="TitlePage" class="position-relative">
        <h2>Page Hire</h2>
        <?= $this->Html->link(__('Back to Departments'), [
            'controller' => 'Departments',
            'action' => 'index'
        ], [
            "class" => "btn button btn-blue position-absolute",
            "style" => "top:0;right:0px"
        ]) ?>
        <?php if (isset($vacancyName) && !is_null($vacancies)) { ?>
        <?php if ($nbVacancies === 1) { ?>
            <h4> <?= __('Vacant position in department of ') . h($vacancyName) ?></h4>
        <?php } else { ?>
            <h4> <?= __('Vacant positions in department of ') . h($vacancyName) ?></h4>
        <?php } ?>
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
                                    'title_no' => $vacancy->title_no,
                                    'dept_no' => $vacancy->dept_no
                                ]
                            ], [
                                'class' => 'btn btn-submit',
                            ]);
                        } ?>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
    <?php } else { ?>

        <?= $this->Flash->render() ?>

    <?php } ?>
</div>
