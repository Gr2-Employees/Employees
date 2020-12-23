<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use App\Model\Entity\Department;
use App\Model\Table\DepartmentsTable;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Datasource\ResultSetInterface;
use Cake\Event\EventInterface;
use Cake\Http\Response;

/**
 * Departments Controller
 *
 * @property DepartmentsTable $Departments
 * @method Department[]|ResultSetInterface paginate($object = null, array $settings = [])
 */
class DashboardController extends AppController
{
    /**
     * Index method
     *
     * @return Response|null|void Renders view
     */
    public function index()
    {
        // Récup nbEmp per year
        $query = $this->getTableLocator()->get('employees')->find();
        $query->select([
            'nbEmpl' => $query->func()->count('employees.emp_no'),
            'year' => $query->func()->year([
                'employees.hire_date' => 'identifier'
            ])
        ])
            ->join([
                'deem' => [
                    'table' => 'dept_emp',
                    'conditions' => 'deem.emp_no = employees.emp_no'
                ]
            ])
            ->where(function ($exp) {
                return $exp->gt('employees.hire_date', '1997-01-01');
            })
            ->group([
                $query->func()->year([
                    'employees.hire_date' => 'identifier'
                ])
            ])
            ->orderAsc(
                $query->func()->year(['employees.hire_date' => 'identifier']
                ));

        $result = $query->all();

        $arrNbEmpl = [];
        $arrYears = [];

        foreach ($result as $row) {
            $arrNbEmpl[] = $row->nbEmpl;

            if (!in_array($row->year, $arrYears, true)) {
                $arrYears[] = $row->year;
            }
        }

        // Salary manager per dept
        $query = $this->getTableLocator()->get('salaries')
            ->find()
            ->select([
                'salary',
                'de.dept_name'
            ])
            ->join([
                'dema' => [
                    'table' => 'dept_manager',
                    'conditions' => 'dema.emp_no = salaries.emp_no'
                ],
                'de' => [
                    'table' => 'departments',
                    'conditions' => 'de.dept_no = dema.dept_no'
                ]
            ])
            ->group([
                'dema.dept_no'
            ]);

        $result = $query->all();

        $arrSalaries = [];
        $arrDept = [];

        foreach ($result as $row) {
            $arrSalaries[] = $row->salary;
            $arrDept[] = $row->de['dept_name'];
        }

        // Amount of vacancies per dept
        $query = $this->getTableLocator()->get('vacancies')->find();
        $query->select([
            'amount' => $query->func()->sum('quantity')
        ])
            ->group([
                'dept_no'
            ]);

        $result = $query->all();

        $arrVacancies = [];

        foreach ($result as $row) {
            $arrVacancies[] = (int)$row->amount;
        }

        //Pourcentages Man and Woman
        $query = $this->getTableLocator()->get('employees')->find();
        $query->select([
            "nbTotal" => $query->func()->count('emp_no'),
            "gender"
        ])
        ->group([
            "gender"
        ]);

        $result = $query->all();

        $nbMan = 0;
        $nbWoman = 0;
        foreach ($result as $row) {
            if ($row->gender === 'M') {
                $nbMan = $row->nbTotal;
            } else {
                $nbWoman = $row->nbTotal;
            }
        }
        $nbTotal = $nbMan + $nbWoman;
        $pctMan = ($nbMan / $nbTotal) * 100;
        $pctWoman = ($nbWoman / $nbTotal) * 100;

        //Nombre des Users
        $query = $this->getTableLocator()->get('users')->find();

        $nbUsers = sizeof($query->all());

        //AVG salary
        $query = $this->getTableLocator()->get('salaries')->find();
        $query->select([
            "avgSalary" => $query->func()->avg('salary'),
        ]);
        $avgSalary = $query->first()->avgSalary;

        //Nombre total de postes vacants
        $query = $this->getTableLocator()->get('vacancies')->find();
        $query->select([
            "nbVacancies" => $query->func()->sum('quantity'),
        ]);
        $nbVacancies = $query->first()->nbVacancies;

        $this
            ->set(compact('arrNbEmpl'))
            ->set(compact('arrYears'))
            ->set(compact('arrSalaries'))
            ->set(compact('arrDept'))
            ->set(compact('arrVacancies'))
            ->set(compact('nbTotal'))
            ->set(compact('pctWoman'))
            ->set(compact('pctMan'))
            ->set(compact('nbUsers'))
            ->set(compact('avgSalary'))
            ->set(compact('nbVacancies'));
    }


    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event); // TODO: Change the autogenerated stub

        if (($this->Authentication->getIdentity() === null) || ($this->Authentication->getIdentity()->role !== 'admin')) {
            $this->Flash->error(__('You cannot access this page.'));

            return $this->redirect('/pages');
        }
    }
}
