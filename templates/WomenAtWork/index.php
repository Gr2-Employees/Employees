<!DOCTYPE html>
<html lang="fr">
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
</head>
<body>
<div id="main">
    <div id="WomenAtWork" class="row div-women-desc">
        <div class="col-6 col-text">
            <h1>Women At Work</h1>
            <?= $this->Text->autoParagraph(
                __('De nos jours, les femmes représentent 40% de l’effectif total au sein de notre banque UnitedSuite (31/12/2019).
            Cela représente ' . $nbFemaleManagers . ' femmes managers dans la banque.
            Elles exercent tous les métiers de la banque. Notre groupe s’attache à faire évoluer l’égalité professionnelle dans toutes ses sphères professionnelles.
            Depuis 2011, il a ainsi développé un programme d’accompagnement des femmes vers les postes de cadres de direction.
            C’est dans ce contexte que l’objectif de 30% de femmes senior managers a été atteint en 2014.
            Pour 2021, l’objectif serais d’augmenter le nombre de femmes travaillant pour UnitedSuite à 50% et ainsi obtenir une paritée égale entre les hommes et les femmes dans notre banque.')
            ) ?>
        </div>
        <div class="col-6">
            <?= $this->Html->image('/img/womenAtWork-page.jpg') ?>
        </div>
    </div>

    <!-- Affichage des graphiques / Charts -->
    <div class="row div-chart">
        <div class="col-12" style="text-align: center;margin-bottom:60px;">
            <h2>Nos statistiques</h2>
        </div>
        <div style="width:40%;">
            <canvas id="womenPieChart"></canvas>
        </div>
        <div style="width:50%;">
            <canvas id="womenLineChart" style="max-height: 400px"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>

<!-- Obligé de mettre ce script ici pour pouvoir manipuler les données de La DDB -->
<script>
    const pieChart = document.getElementById('womenPieChart').getContext('2d');
    const chartPie = new Chart(pieChart, {
        type: 'pie',
        data: {
            labels: ['Women (%)', 'Men (%)'],
            datasets: [{
                backgroundColor: [
                    'rgb(241,178,255)',
                    'rgb(143,230,250)',
                ],
                borderColor: 'rgb(255,255,255)',
                data: [
                    <?= $this->Number->format($pctWomen, [
                        'precision' => '2'
                    ]) ?>,
                    <?= $this->Number->format($pctMen, [
                        'precision' => '2'
                    ])?>
                ]
            }]
        },
        options: {
            title: {
                display: 'true',
                text: 'Proportion de femmes et d’hommes dans l\'entreprise'
            }
        }
    });
</script>
<script>
    const lineChart = document.getElementById('womenLineChart').getContext('2d');
    const chartLine = new Chart(lineChart, {
        type: 'line',

        // The data for our dataset
        data: {
            labels: [ <?php if (isset($arrYears)) {
                foreach ($arrYears as $year) {
                    echo $year . ',';
                }
            } ?>
            ],
            datasets: [{
                label: 'Femmes dans l\'entreprise',
                backgroundColor: 'transparent',
                borderColor: 'rgb(252,138,233)',
                data: [ <?php if (isset($arrWomen)) {
                    foreach ($arrWomen as $nb) {
                        echo $nb . ',';
                    }
                } ?>
                ]
            }]
        },

        // Configuration options go here
        options: {
            title: {
                display: 'true',
                text: 'Évolution du nombre de femmes par année'
            }
        }
    });
</script>
</body>
</html>

