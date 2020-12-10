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
                ]
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

        $tabManagF = array();
        $cpt = 0;
        foreach ($results as $row) {
            $tabManagF[] = $row["first_name"] . " " . $row["last_name"];
            $cpt++;
        };
        var_dump($tabManagF);
    }


    public function beforeRender(EventInterface $event)
    {
        parent::beforeRender($event);
    }
}
