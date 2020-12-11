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
            'gender' => 'F',
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
        $cpt = 0;

        foreach ($results as $row) {
            $femaleManagers[] = $row["first_name"] . " " . $row["last_name"];
            $cpt++;
        }

        $this
            ->set(compact('pctWomen'))
            ->set(compact('pctMen'))
            ->set('arrWomen', $arrNbWomen)
            ->set('arrYears', $arrYears)
            ->set('femaleManagers', $femaleManagers)
            ->set('nbFemaleManagers', $cpt);
    }


    public function beforeRender(EventInterface $event)
    {
        parent::beforeRender($event);
    }
}
