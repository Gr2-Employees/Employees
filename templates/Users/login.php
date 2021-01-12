<?= $this->Flash->render() ?>
<div id="login-background" class="row">
    <div id="form-login" class="users form col-6">
        <h3 class="mt-2">Login</h3>
        <?= $this->Form->create() ?>
        <fieldset>
            <legend><?= __('Please enter your email and password') ?></legend>
            <!-- Email field -->
            <?= $this->Form->control('email', [
                'required' => true
            ]) ?>

            <!-- Password field -->
            <?= $this->Form->control('password', [
                'required' => true
            ]) ?>
        </fieldset>

        <!-- Button Submit -->
        <?= $this->Form->submit(__('Login'), [
            'id' => 'btn-form-login'
        ]) ?>
        <?= $this->Form->end() ?>
    </div>
</div>
