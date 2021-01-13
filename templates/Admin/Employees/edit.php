<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Employee $employee
 */
?>
<div class="row row-styled-background">
    <div class="column-responsive column-80 mt-5 mx-auto">
        <div class="employees form content position-relative p-5">
            <?= $this->Form->create($employee, [
                'type' => 'file'
            ]) ?>
            <fieldset>
                <legend><?= __('Edit Employee') ?></legend>
                <!-- Button admin/dept/index -->
                <?= $this->Html->link(__('List Employees'), [
                    'prefix' => 'Admin',
                    'action' => 'index'
                ], [
                    'class' => 'button btn-blue position-absolute',
                    'style' => "top: 40px;right: 40px"
                ]) ?>
                <div class="row">
                    <div class="col-8 mt-4">
                        <!-- Firstname field -->
                        <?= $this->Form->control('first_name', [
                            'required' => 'true'
                        ]) ?>

                        <!-- Lastname field -->
                        <?= $this->Form->control('last_name', [
                            'required' => 'true'
                        ]) ?>

                        <!-- Gender select field -->
                        <?= $this->Form->control('gender', [
                            'required' => 'true',
                            'options' => [
                                'M' => 'M',
                                'F' => 'F',
                                'Others' => 'Others'
                            ]
                        ]) ?>
                    </div>
                    <div class="col-4 mt-4">
                        <?php if (is_null($employee->picture)) {
                            echo $this->Html->image('/img/noUserPic.jpg', [
                                'class' => 'manager-picture mb-4 float-right'
                            ]);
                        } else {
                            echo $this->Html->image('/img/uploads/emp_pictures/' . $employee->picture, [
                                'alt' => 'Photo de l\'employee' . $employee->emp_no . '.',
                                'class' => 'manager-picture mb-4 float-right'
                            ]);
                        }
                        // Picture file field
                        echo $this->Form->control('picture', [
                            'label'=>'',
                            'class'=>'float-right',
                            'required'=>'false',
                            'type' => 'file'
                        ]);
                        ?>
                    </div>
                    <div class="column">
                    <?= $this->Form->control('birth_date', [
                        'required' => 'true'
                    ]) ?>
                    <?= $this->Form->control('email', [
                        'required' => 'true'
                    ]) ?>

                    <?= $this->Form->control('hire_date', [
                        'required' => 'true'
                    ]) ?>
                    </div>
                </div>
            </fieldset>
            <?= $this->Form->button(__('Submit'), ['class'=>'btn-blue ml-3']) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
