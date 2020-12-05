<h3>Liste des 10 premiers employés par genre</h3>
<ul>
<?php foreach($employees as $employee) : ?>
    <li><?= h($employee->first_name)." ".h($employee->last_name)
        ." (".h($employee->gender).")" ?></li>
<?php endforeach; ?>
</ul>