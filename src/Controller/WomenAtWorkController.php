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

        $all = $nbWomen + $nbMen;

        $pctMen = ($nbMen / $all) * 100;
        $pctWomen = ($nbWomen / $all) * 100;
        //var_dump($nbMen); // 179973
        //var_dump($nbWomen); // 120051

        $this
            ->set(compact('pctWomen'))
            ->set(compact('pctMen'));


        /**
         * Récupération du nombre de femmes par année
         * 1990-1995-2000-2002
         */
        $arrNbWomen = [];
        $arrYears = ['1990', '1995', '2000', '2002'];
        $query = $this->getTableLocator()->get('dept_emp')->find();

        $query->select([
            'count' => $query->func()->count('*')
        ])
        ->join([
            'em' => [
                'table' => 'employees',
                'conditions' => 'em.emp_no = dept_emp.emp_no'
            ]
        ])
        ->where([
            'em.gender' => 'F'
        ])
        ->where(
            function ($exp) {
                return $exp->like('to_date', '%1990%');
            }
        );

        $arrNbWomen[] = $query->first()->count;

        // 1995
        $query = $this->getTableLocator()->get('dept_emp')->find();

        $query->select([
            'count' => $query->func()->count('*')
        ])
        ->join([
            'em' => [
                'table' => 'employees',
                'conditions' => 'em.emp_no = dept_emp.emp_no'
            ]
        ])
        ->where([
            'em.gender' => 'F'
        ])
        ->where(
            function ($exp) {
                return $exp->like('to_date', '%1995%');
            }
        );

        $arrNbWomen[] = $query->first()->count;

        // 2000
        $query = $this->getTableLocator()->get('dept_emp')->find();

        $query->select([
            'count' => $query->func()->count('*')
        ])
        ->join([
            'em' => [
                'table' => 'employees',
                'conditions' => 'em.emp_no = dept_emp.emp_no'
            ]
        ])
        ->where([
            'em.gender' => 'F'
        ])
        ->where(
            function ($exp) {
                return $exp->like('to_date', '%2000%');
            }
        );

        $arrNbWomen[] = $query->first()->count;

        // 2002
        $query = $this->getTableLocator()->get('dept_emp')->find();

        $query->select([
            'count' => $query->func()->count('*')
        ])
        ->join([
            'em' => [
                'table' => 'employees',
                'conditions' => 'em.emp_no = dept_emp.emp_no'
            ]
        ])
        ->where([
            'em.gender' => 'F'
        ])
        ->where(
            function ($exp) {
                return $exp->like('to_date', '%2002%');
            }
        );

        $arrNbWomen[] = $query->first()->count;
        var_dump($arrNbWomen);
        $this->set('arrWomen', $arrNbWomen)
            ->set('arrYears', $arrYears);
        /**
         * Récupération des femmes managers
         */
        $query = $this->getTableLocator()->get('employees')
            ->find()
            ->select([
                'dema.dept_no',
                'employees.first_name',
                'employees.last_name',
                'employees.emp_no',
            ])
            ->join([
                'dema' => [
                    'table' => 'dept_manager',
                    'conditions' => 'dema.emp_no = employees.emp_no'
                ],
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

        $femaleManager = [];
        $cpt = 0;

        foreach ($results as $row) {
            $femaleManager[] = $row["first_name"] . " " . $row["last_name"];
            $cpt++;
        }
        var_dump($cpt);
        var_dump($femaleManager);
    }


    public function beforeRender(EventInterface $event)
    {
        parent::beforeRender($event);
    }
}
