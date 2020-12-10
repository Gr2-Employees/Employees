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
    <h1>Women At Work</h1>
    <div id="presentationText">
        <?= $this->Text->autoParagraph(
            __('The organization began by offering a resource room equipped with job listings and library books
                on women’s employment as well as holding seminars and workshops on career fields and career planning classes.
                For about three decades, our organization has provided job skill training, employment preparation and job search services to thousands of job seekers.')
        ) ?>

        <?= $this->Text->autoParagraph(
            __('As per research, companies who have a higher number of women in their workforce have gained high financial
                profits and productivity as their output, when compared to the companies which have fewer women employees. Being in this 21st century, where women have been equally capable and successful as men, gender diversity at
                the workplace should be made a mandatory rule.
                Moreover, the companies which have a diverse workforce are more successful when compared to other companies which are mostly male-dominated.')
        ) ?>
    </div>

    <!-- Affichage des graphiques / Charts -->
    <div style="width:500px; height:350px;">
        <h2>Nos statistiques</h2>
        <h6>Lines</h6>
        <canvas id="womenPieChart"></canvas>
        <hr/>
        <canvas id="womenLineChart"></canvas>
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
            labels: [<?php foreach ($arrYears as $year) {
                echo $year . ',';
            } ?>
            ],
            datasets: [{
                backgroundColor: 'transparent',
                borderColor: 'rgb(252,138,233)',
                data: [<?php foreach ($arrWomen as $nb) {
                    echo $nb . ',';
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

