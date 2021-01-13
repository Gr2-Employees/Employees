<div id="container" class="row">
    <aside id="aside">
        <!-- Lien vers admin/dept/index -->
        <?= $this->Html->link('To Departments', [
            'prefix' => 'Admin',
            'controller' => 'Departments',
            'action' => 'index'
        ], [
            'class' => 'aside-links'
        ]) ?>

        <!-- Lien vers admin/Employees/index -->
        <?= $this->Html->link('To Employees', [
            'prefix' => 'Admin',
            'controller' => 'Employees',
            'action' => 'index'
        ], [
            'class' => 'aside-links'
        ]) ?>

        <!-- Lien vers admin/Users/index -->
        <?= $this->Html->link('To Users', [
            'prefix' => 'Admin',
            'controller' => 'users',
            'action' => 'index'
        ], [
            'class' => 'aside-links'
        ]) ?>

        <!-- Lien vers admin/Demands/index -->
        <?= $this->Html->link('To Demands', [
            'controller' => '../Demands',
            'action' => 'index'
        ], [
            'class' => 'aside-links'
        ]) ?>

        <!-- Lien vers admin/Titles/index -->
        <?= $this->Html->link('To Titles', [
            'prefix' => 'Admin',
            'controller' => 'Titles',
            'action' => 'index'
        ], [
            'class' => 'aside-links'
        ]) ?>

    </aside>
    <div id="data">
        <div class="row" id="dataStat">
            <div class="column-25 stat1 text-center">
                <div class="row" style="height: 55%">
                    <!-- Amount of employees -->
                    <div class="col" style="line-height: 40px;">
                        <h4 class="h4-stat"><?= __('Total employees') ?></h4>
                        <p class="dash-p"><?= $this->Number->format($nbTotal) ?></p>
                    </div>
                </div>
                <div class="row" id="ratio">
                    <!-- Percentage of men -->
                    <div class="col-6">
                        <h5 class="h5-stat"><?= __('% Men') ?></h5>
                        <p lass="dash-p"><?= $this->Number->toPercentage($pctMan) ?></p>
                    </div>
                    <!-- Percentage of women -->
                    <div class="col-6">
                        <h5 class="h5-stat"><?= __('% Women') ?></h5>
                        <p lass="dash-p"><?= $this->Number->toPercentage($pctWoman) ?></p>
                    </div>
                </div>
            </div>
            <div class="column-25 stat1">
                <div class="row" style="transform: translate(0%, 40%);">
                    <!-- Amount of users -->
                    <div class="col text-center" style="line-height: 70px;">
                        <h4 class="h5-stat"><?= __('Total users') ?></h4>
                        <p lass="dash-p"><?= $nbUsers . ' user(s) ' ?></p>
                    </div>
                </div>
            </div>
            <div class="column-25 stat1">
                <div class="row" style="transform: translate(0%, 40%);">
                    <!-- Average Salary -->
                    <div class="col text-center" style="line-height: 70px;">
                        <h4 class="h5-stat"><?= __('Average salary') ?></h4>
                        <p lass="dash-p"><?= $this->Number->format($avgSalary, [
                                'precision' => 2,
                                'after' => '$'
                            ]); ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="column-25 stat1">
                <div class="row" style="transform: translate(0%, 40%);">
                    <div class="col text-center" style="line-height: 70px;">
                        <h4 class="h5-stat"><?= __('Total vacancies') ?></h4>
                        <p lass="dash-p"><?= $nbVacancies . ' vacancies ' ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Div first two charts -->
        <div class="row" id="chart">
            <!-- Line Chart -->
            <div class="col-6 text-center">
                <h3><?= __('Number of employees per year') ?></h3>
                <canvas id="lineChart" width="100px" height="50"></canvas>
            </div>
            <!-- Horizontal bars Chart -->
            <div class="col-6 text-center">
                <h3><?= __('Manager salary per department') ?></h3>
                <canvas id="horizontalBarChart" width="100px" height="50"></canvas>
            </div>
        </div>

        <!-- Div verticalBar -->
        <div class="row" id="verticalChart">
            <div class="col text-center">
                <h3><?= __('Vacancies amount per department') ?></h3>
                <canvas id="verticalBarChart" width="200" height="60"></canvas>
            </div>

        </div>

    </div>
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
                label: 'Number of employees per year',
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
                    'rgba(255, 99, 132, 0.5)',
                    'rgba(54, 162, 235, 0.5)',
                    'rgba(255, 206, 86, 0.5)',
                    'rgba(75, 192, 192, 0.5)',
                    'rgba(153, 102, 255, 0.5)',
                    'rgba(237,71,255,0.5)',
                    'rgba(135,255,102,0.5)',
                    'rgba(121,148,241,0.5)',
                    'rgba(255, 159, 64, 0.5)'
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
                }],
                xAxes: [{
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
                label: 'Number of vacancies per department',
                data: [<?php foreach ($arrVacancies as $vacancy) {
                    echo $vacancy . ',';
                } ?>],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.5)',
                    'rgba(54, 162, 235, 0.5)',
                    'rgba(255, 206, 86, 0.5)',
                    'rgba(75, 192, 192, 0.5)',
                    'rgba(153, 102, 255, 0.5)',
                    'rgba(237,71,255,0.5)',
                    'rgba(135,255,102,0.5)',
                    'rgba(121,148,241,0.5)',
                    'rgba(255, 159, 64, 0.5)'
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
                        beginAtZero: true,
                        max: 14
                    }
                }]
            }
        }
    });
</script>
