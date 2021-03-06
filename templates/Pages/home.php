<!DOCTYPE html>
<html lang="fr">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->css(['home']) ?>
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
<div class="content content-home">
    <div id="div1" class="row">
        <div class="col-6">
            <h2>Lorem ipsum dolor sit amet</h2>
            <h1><?= strtoupper('Curabitur cursus') ?></h1>
            <p> Lorem ipsum dolor sit amet, consectetur adipiscing elit.<br> Pellentesque vel volutpat felis,
                eu condimentum. lorem ipsum dolor. lorem ipsum dolor sit amt.</p>
            <?= $this->Html->link(__('A propos'), '/pages/about-us', [
                'class' => 'btn btn-primary btn-home',
                'style' => 'font-size : 10pt'
            ]) ?>
        </div>
    </div>
    <div id="div2" class="row">
        <!-- Div department -->
        <div id="div2-1" class="col-5">
            <!-- Div department pic -->
        </div>
        <div id="div2-2" class="col-7">
            <h2>Lorem ipsum dolor sit amet</h2>
            <h1><?= strtoupper('Curabitur cursus') ?></h1>
            <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit.<br> Pellentesque vel volutpat felis,
                eu condimentum. lorem ipsum dolor. lorem ipsum dolor sit amt.
            </p>
            <?= $this->Html->link(__('Découvrir nos departements'), '/departments', [
                'class' => 'btn btn-primary btn-home',
                'style' => 'border-radius: 19px;
                            background: linear-gradient(45deg, #3e515a, #4a606b);
                            box-shadow:  13px -13px 29px #374850,
                            -13px 13px 29px #536c78;'
            ]) ?>
        </div>
    </div>

    <!-- Div Employees -->
    <div id="div3" class="row">
        <div id="div3-1" class="col-6">
            <h2>Lorem ipsum dolor sit amet</h2>
            <h1><?= strtoupper('Curabitur cursus') ?></h1>
            <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit.<br> Pellentesque vel volutpat felis,
                eu condimentum. lorem ipsum dolor. lorem ipsum dolor sit amt.
            </p>
            <?= $this->Html->link(__('Plus d\'info sur nos employés'), '/employees', [
                'class' => 'btn btn-primary btn-home'
            ]) ?>
        </div>
    </div>

    <!-- Div womentAtWork -->
    <div id="div4" class="row">
        <div id="div4-1" class="col-6">
            <h2>Lorem ipsum dolor sit amet</h2>
            <h1><?= strtoupper('Curabitur cursus') ?></h1>
            <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit.<br> Pellentesque vel volutpat felis,
                eu condimentum. lorem ipsum dolor. lorem ipsum dolor sit amt.
            </p>
            <?= $this->Html->link(__('Women at work'), '/WomenAtWork', [
                'class' => 'btn btn-primary btn-home',
                'style' => 'border-radius: 19px;
                                    background: linear-gradient(45deg, #8163b4, #9976d6);
                                    box-shadow:  13px -13px 29px #7258a0,
                                    -13px 13px 29px #ac84f0;'
            ]) ?>
        </div>
        <div id="div4-2" class="col-5">
            <!-- womenAtWork pic -->
        </div>
    </div>
    <div id="news">
        <div id="divTitle" class="col">
            <h2><?= __('Communiqués de presse') ?></h2>
        </div>
        <div class="row">
            <!-- Article 1 -->
            <div id="article1" class=" articles">
                <?= $this->Html->link(
                    $this->Html->image('article1.png', [
                        "alt" => "article1",
                        "class" => "img-article"
                    ]),
                    '/files/IBEN_09_Cashless_20200825_FR_V3.pdf', [
                    'target' => '_blank',
                    'escape' => false
                ]) ?>

                <h6>Vers un monde sans cash ?</h6>
                <p>La fermeture des magasins physiques pendant le confinement,
                    la distanciation sociale et la crainte que les billets de banque soient porteurs du virus a conduit
                    à une forte...
                </p>
                <?= $this->Html->link(__('<i class="fas fa-arrow-right"></i> Lire plus'),
                    '/files/IBEN_09_Cashless_20200825_FR_V3.pdf', [
                        'class' => 'button btn-articles',
                        'target' => '_blank',
                        'escape' => false
                    ]) ?>
            </div>

            <!-- Article 2 -->
            <div id="article2" class="articles">
                <?= $this->Html->link(
                    $this->Html->image('article2.jpg', [
                        "alt" => "article2",
                        "class" => "img-article"
                    ]),
                    '/files/IBEN_11_Transactions_Update_ete_20200904_FR_V3.pdf', [
                    'target' => '_blank',
                    'escape' => false
                ]) ?>

                <h6>Après le rebond, pas de véritable reprise pour les
                    dépenses des Belges</h6>
                <p>La crise du coronavirus est loin d’être terminée et le confinement du printemps a fait
                    de sérieux dégâts à l’économie belge...</p>
                <?= $this->Html->link(__('<i class="fas fa-arrow-right"></i> Lire plus'), '/files/IBEN_11_Transactions_Update_ete_20200904_FR_V3.pdf', [
                    'class' => 'button btn-articles',
                    'target' => '_blank',
                    'escape' => false
                ]) ?>
            </div>

            <!-- Article 3 -->
            <div id="article3" class="articles">
                <?= $this->Html->link(
                    $this->Html->image('article3.jpg', [
                        "alt" => "article3",
                        "class" => "img-article"
                    ]),
                    '/files/Post_covid_consumer_FR.pdf', [
                    'target' => '_blank',
                    'escape' => false
                ]) ?>

                <h6>Un consommateur plus soucieux du développement durable ?</h6>
                <p>Avant la crise du coronavirus, le développement durable était devenu une préoccupation de plus en
                    plus sensible...</p>
                <?= $this->Html->link(__('<i class="fas fa-arrow-right"></i> Lire plus'), '/files/Post_covid_consumer_FR.pdf', [
                    'class' => 'button btn-articles',
                    'target' => '_blank',
                    'escape' => false
                ]) ?>
            </div>

            <!-- Article 4 -->
            <div id="article4" class="articles">
                <?= $this->Html->link(
                    $this->Html->image('article4.jpg', [
                        "alt" => "article4",
                        "class" => "img-article"
                    ]),
                    '/files/IBEN-FR.pdf', [
                    'target' => '_blank',
                    'escape' => false
                ]) ?>

                <h6>Comment les Belges appréhendent-ils leurs dettes ?</h6>
                <p>Les Belges conservent une attitude relativement prudente vis-à-vis de l’endettement
                    non hypothécaire. C’est un des résultats qui ressort de la nouvelle ING ...</p>

                <?= $this->Html->link(__('<i class="fas fa-arrow-right"></i> Lire plus'), '/files/IBEN-FR.pdf', [
                    'class' => 'button btn-articles',
                    'target' => '_blank',
                    'escape' => false
                ]) ?>
            </div>
        </div>
    </div>

    <!-- Div Partners -->
    <div id="partners" class="row">
        <div class="col-12">
            <h2>Partenaires</h2>
        </div>
        <?php foreach ($partners as $partner) { ?>
            <div class="col-2 partners-cadre">
                <?= $this->Html->link(
                    $this->Html->image($partner->logo, [
                        "alt" => $partner->title,
                    ]),
                    $partner->url, [
                    'target' => '_blank',
                    'escape' => false
                ]) ?>
            </div>
        <?php } ?>
    </div>

    <!-- Div Maps + QR Code-->
    <div id="div7" class="row">
        <!-- iFrame -->
        <div id="div-iframe" class="col-6">
            <h3>Visitez-nous</h3>
            <iframe src="https://www.google.com.qa/maps/d/u/0/embed?mid=1RoH2nl4fnIkasD6Ta4o2WFJajUcRups9" width="640" height="480"></iframe>
        </div>

        <!-- QR Code-->
        <div class="col-6">
            <div>
                <h3>Rejoignez-nous via notre Appli Unitedsuite</h3>
            </div>
            <?= $this->Html->image('qr-code.png', [
                "width" => "400px",
                "height" => "400px",
            ]) ?>
        </div>
    </div>
</div>
</body>

<!-- Footer -->
<footer>
    <div id="div-footer" class="row">
        <div class="col-4 footer-lign">
            <div class="row">
                <!-- Left side footer links -->
                <div class="col-6">
                    <h6>À propos</h6>
                    <p class="footer-links"><?= $this->Html->link(__('Qui sommes-nous?'), '/pages/about-us', ['target' => '_blank']) ?></p>
                    <p class="footer-links"><?= $this->Html->link(__('News'), '#news') ?></p>
                    <h6>Rapport annuel</h6>
                    <p class="footer-links"><?= $this->Html->link(__('Rapport annuel'), '/files/rapport-annuel-2019.pdf', ['target' => '_blank']) ?></p>
                </div>
                <div class="col-6">
                    <h6>Plus d'infos</h6>
                    <p class="footer-links"><?= $this->Html->link(__('Découvrir nos départements'), '/departments', ['target' => '_blank']) ?></p>
                    <p class="footer-links"><?= $this->Html->link(__('À propos nos employées'), '/employees', ['target' => '_blank']) ?></p>
                    <p class="footer-links"><?= $this->Html->link(__('Women At Work'), '/WomenAtWork/index', ['target' => '_blank']) ?></p>
                </div>
            </div>
        </div>
        <!-- Middle social networking links -->
        <div class="col-4 footer-lign">
            <h6>Visitez nos réseaux sociaux</h6>
            <div class="row" id="row-social">
                <?= $this->Html->link(__('<i class="fab fa-3x fa-facebook"></i>'), 'https://www.facebook.com/bpost.official/', [
                    'class' => 'sm-icons',
                    'target' => '_blank',
                    'escape' => false
                ]) ?>
                <?= $this->Html->link(__('<i class="fab fa-3x fa-twitter"></i>'), 'https://twitter.com/bpost_fr', [
                    'class' => 'sm-icons',
                    'target' => '_blank',
                    'escape' => false
                ]) ?>
                <?= $this->Html->link(__('<i class="fab fa-3x fa-reddit"></i>'), 'https://www.reddit.com/', [
                    'class' => 'sm-icons',
                    'target' => '_blank',
                    'escape' => false
                ]) ?>
                <?= $this->Html->link(__('<i class="fab fa-3x fa-linkedin-in"></i>'), 'https://www.linkedin.com/company/bpost/', [
                    'class' => 'sm-icons',
                    'target' => '_blank',
                    'escape' => false
                ]) ?>
                <?= $this->Html->link(__('<i class="fab fa-3x fa-instagram"></i>'), 'https://www.instagram.com/bpost/?hl=fr', [
                    'class' => 'sm-icons',
                    'target' => '_blank',
                    'escape' => false
                ]) ?>
            </div>
        </div>

        <!-- Links to AppStores -->
        <div class="col-4">
            <h6>Rejoignez-nous sur l'appli Unitedsuite</h6>
            <div class="row">
                <div class="col">
                    <?= $this->Html->link(
                        $this->Html->image('googleplay.png', [
                            "alt" => "GooglePlay",
                            "class" => "stores"
                        ]),
                        'https://play.google.com/store/apps/details?id=com.bpb.mobilebanking.smartphone.prd&gl=BE', [
                        'target' => '_blank',
                        'escape' => false
                    ]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <?= $this->Html->link(
                        $this->Html->image('appstore.jpg', [
                            "alt" => "AppStore",
                            "class" => "stores"
                        ]),
                        'https://apps.apple.com/fr/app/mobilebanking-smartphone/id1278930217', [
                        'target' => '_blank',
                        'escape' => false
                    ]) ?>
                </div>
            </div>
        </div>
        <!-- Credentials -->
        <div class="row">
            <div style="color:white;" class="col text-center">
                &copy Nathan, Radad, Thomas et Kwasi
            </div>
        </div>
    </div>

</footer>
</html>
