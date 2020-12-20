<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;

/**
 * Partners Controller
 *
 * @property \App\Model\Table\PartnersTable $Partners
 * @method \App\Model\Entity\Partner[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class WomenAtWorkController extends AppController
{
    public function index()
    {
        /**
         * Comparaison du nombre de femmes et d'hommes dans l'entreprise
         */
        $query = $this->getTableLocator()->get('employees')->find();

        $query->select([
            'count' => $query->func()->count('employees.emp_no'),
            'employees.gender'
        ])
            ->group([
                'employees.gender'
            ]);

        $result = $query->all();

        $nbMen = 0;
        $nbWomen = 0;

        foreach ($result as $row) {
            if ($row->gender === 'M') {
                $nbMen = $row->count;
            } else {
                $nbWomen = $row->count;
            }
        }

        $allEmployees = $nbWomen + $nbMen;

        // Pourcentages Men/Women
        $pctMen = ($nbMen / $allEmployees) * 100;
        $pctWomen = ($nbWomen / $allEmployees) * 100;

        /**
         * Récupération du nombre de femmes par année
         */

        $arrNbWomen = [];
        $arrYears = [];

        // Query
        $query = $this->getTableLocator()->get('employees')->find();
        $query->select([
                'nbFemmes' => $query->func()->count('employees.emp_no'),
                'hire_date'
        ])
        ->where([
            'gender' => 'F'
        ])
        ->group([
            'hire_date'
        ]);

        $result = $query->all();

        foreach($result as $row) {
            $arrNbWomen[] = $row->nbFemmes;

            // Get years
            $year = $row->hire_date->format('Y');
            if (!in_array($year, $arrYears, true)) {
                $arrYears[] = $year;
            }
        }

        /**
         * Récupération des femmes managers (noms + nombre)
         */
        $query = $this->getTableLocator()->get('employees')
            ->find()
            ->select([
                'employees.first_name',
                'employees.last_name',
                'employees.emp_no',
            ])
            ->join([
                'emti' => [
                    'table' => 'employee_title',
                    'conditions' => 'emti.emp_no = employees.emp_no'
                ],
            ])
            ->where([
                'emti.title_no' => '7',
                'employees.gender' => 'F'
            ]);

        $results = $query->all();

        $femaleManagers = [];
        $cptManagers = 0;

        foreach ($results as $row) {
            $femaleManagers[] = $row["first_name"] . " " . $row["last_name"];
            $cptManagers++;
        }
        /**
         * Récupération des 3 départements ayant le plus de femmes (nombre)
         * Pas implémenté car la requête prend trop de temps à générer le résultat
         *  -> nombres trop importants d'employés
         * Résults :
         * dept_no    dept_name    nbFemales
         *   d005    Development    34258
         *   d004    Production     29549
         *   d007    Sales          20854


            $query = $this->getTableLocator()->get('dept_emp')->find();
            $query->select([
                'dept_emp.dept_no',
                'nbFemales' => $query->func()->count('em.emp_no')
            ])
            ->join([
                'em' => [
                    'table' => 'employees',
                    'conditions' => 'em.emp_no = dept_emp.emp_no'
                ],
                'dep' => [
                    'table' => 'departments',
                    'conditions' => 'dep.dept_no = dept_emp.dept_no'
                ]
            ])
            ->where([
                'em.gender' => 'F'
            ])
            ->group([
                'dep.dept_no'
            ])
            ->orderDesc('nbFemales')
            ->limit(3);

             $descNbWomen = $query->all();
         */

        $this
            ->set(compact('pctWomen'))
            ->set(compact('pctMen'))
            ->set('arrWomen', $arrNbWomen)
            ->set('arrYears', $arrYears)
            ->set('femaleManagers', $femaleManagers)
            ->set('nbFemaleManagers', $cptManagers);
    }
}
