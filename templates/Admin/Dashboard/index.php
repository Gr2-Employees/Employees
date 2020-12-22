<?php

if (isset($welcomeMessage)) {
    echo $welcomeMessage;
}
?>

<!-- TODO: Ajouter des cells pour des quick infos sur les différentes parties du back-office -->
<!-- Lien vers admin/dept/index-->
<?= $this->Html->link('To departments', [
    'prefix' => 'Admin',
    'controller' => 'Departments',
    'action' => 'index'
]) ?>

<hr/>

<!-- Lien vers admin/Employees/index-->
<?= $this->Html->link('To Employees', [
    'prefix' => 'Admin',
    'controller' => 'Employees',
    'action' => 'index'
]) ?>

<hr/>

<!-- Lien vers admin/Users/index-->
<?= $this->Html->link('To Users', [
    'prefix' => 'Admin',
    'controller' => 'users',
    'action' => 'index'
]) ?>

<!-- Div lineChart & horizontalBar -->
<div class="row" id="chart" style="width:50%; height:20%">
    <canvas id="lineChart" width="200" height="100"></canvas>
    <canvas id="horizontalBarChart" width="200" height="100"></canvas>
</div>

<!-- Div verticalBar -->
<div class="row" id="verticalChart" style="width:100%; height:10%">
    <canvas id="verticalBarChart" width="200" height="100"></canvas>
</div>

<!-- Bar chart nbEmpl per year -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<script>
    var ctx = document.getElementById('lineChart').getContext('2d');
    var lineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [<?php foreach ($arrYears as $year) {
                echo $year . ',';
            } ?> ],
            datasets: [{
                label: 'NbEmpl per year',
                data: [<?php foreach ($arrNbEmpl as $nbEmpl) {
                    echo $nbEmpl . ',';
                } ?>],
                backgroundColor: 'transparent',
                borderColor: 'pink'
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
</script>

<!-- Manager salaries per dept -->
<script>
    var ctx = document.getElementById('horizontalBarChart').getContext('2d');
    var horizontalBarChart = new Chart(ctx, {
        type: 'horizontalBar',
        data: {
            labels: [<?php foreach ($arrDept as $dept) {
                echo '"' . $dept . '"' . ',';
            }?>],
            datasets: [{
                label: 'Salary (in $)',
                data: [<?php foreach ($arrSalaries as $salary) {
                    echo $salary . ',';
                } ?>],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(237,71,255,0.2)',
                    'rgba(135,255,102,0.2)',
                    'rgba(121,148,241,0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(237,71,255,1)',
                    'rgba(135,255,102,1)',
                    'rgba(121,148,241,1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
</script>

<script>
    var ctx = document.getElementById('verticalBarChart').getContext('2d');
    var verticalBarChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [<?php foreach ($arrDept as $dept) {
                echo '"' . $dept . '"' . ',';
            }?>],
            datasets: [{
                label: 'Number of vacancies',
                data: [<?php foreach ($arrVacancies as $vacancy) {
                    echo $vacancy . ',';
                } ?>],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(237,71,255,0.2)',
                    'rgba(135,255,102,0.2)',
                    'rgba(121,148,241,0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(237,71,255,1)',
                    'rgba(135,255,102,1)',
                    'rgba(121,148,241,1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
</script>
