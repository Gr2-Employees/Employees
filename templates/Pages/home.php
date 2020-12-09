<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?= $this->fetch('title') ?>
    </title>
    <link href="https://fonts.googleapis.com/css?family=Raleway:400,700" rel="stylesheet">
    <?= $this->Html->css(['home']) ?>
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
    <div class="content content-home">
        <div id="div1" class="row">
            <div  class="col-6">
                <h2>Welcome To Gusto Restaurant</h2>
                <h1>LOVES HEALTHY FOOD</h1>
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit.<br> Pellentesque vel volutpat felis,
                    eu condimentum. lorem ipsum dolor. lorem ipsum dolor sit amt.
                </p>
                <a class="btn btn-primary btn-home" href="#">A propos</a>
            </div>
        </div>
        <div id="div2" class="row">
            <div id="div2-1" class="col-5">

            </div>
            <div  id="div2-2" class="col-7">
                <h2>Welcome To Gusto Restaurant</h2>
                <h1>LOVES HEALTHY FOOD</h1>
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit.<br> Pellentesque vel volutpat felis,
                    eu condimentum. lorem ipsum dolor. lorem ipsum dolor sit amt.
                </p>
                <?= $this->Html->link(__('Découvrir nos departements'),'/departments',[
                    'class' => 'btn btn-primary btn-home'
                ])?>
            </div>
        </div>
        <div id="div3" class="row">
            <div  id="div3-1" class="col-6">
                <h2>Welcome To Gusto Restaurant</h2>
                <h1>LOVES HEALTHY FOOD</h1>
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit.<br> Pellentesque vel volutpat felis,
                    eu condimentum. lorem ipsum dolor. lorem ipsum dolor sit amt.
                </p>
                <?= $this->Html->link( __('Plus d\'info sur nos employés'), '/employees', [
                    'class' => 'btn btn-primary btn-home',
                    'style' => 'font-size : 10pt'
                ]) ?>
            </div>
        </div>
    </div>

</body>
</html>
