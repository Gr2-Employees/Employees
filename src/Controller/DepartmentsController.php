<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Entity\Department;
use App\Model\Table\DepartmentsTable;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Datasource\ResultSetInterface;
use Cake\Http\Response;

/**
 * Departments Controller
 *
 * @property DepartmentsTable $Departments
 * @method Department[]|ResultSetInterface paginate($object = null, array $settings = [])
 */
class DepartmentsController extends AppController
{
    /**
     * Index method
     *
     * @return Response|null|void Renders view
     */
    public function index()
    {
        $departments = $this->paginate($this->Departments);


        $this->set(compact('departments'));
    }

    /**
     * View method
     *
     * @param string|null $id Department id.
     * @return Response|null|void Renders view
     * @throws RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $department = $this->Departments->get($id, [
            'contain' => [],
        ]);

        /**
         * Recherche du nombre total d'employés du département
         */
        $query = $this->getTableLocator()->get('dept_emp')->find();
        $query->select([
            'count' => $query->func()->count('*')
        ])
        ->where([
            'dept_emp.dept_no' => $id
        ]);

        $nbEmpl = $query->first()->count;

        /**
         * Nombres de postes vacants du department
         */
        $query = $this->getTableLocator()->get('vacancies')->find();
        $query->select([
            'nbVacant' => $query->func()->sum('quantity')
        ])
        ->where([
            'dept_no' => $id
        ])
        ->group([
            'title_no'
        ]);

        if (is_null($query->all()->count())) {
            // If no vacant spot
            $nbVacants = 0;
        } else {
            $nbVacants = $query->all()->count();
        }

        /**
         * Récupération de la photo du manager
         */
        $query = $this->getTableLocator()->get('dept_manager')
        ->find()
        ->select([
            'dept_manager.picture'
        ])
        ->where([
            'dept_no' => $id,
            'to_date' => '9999-01-01'
        ]);

        $picture = $query->first()->picture;

        /**
         * Récupération du nom du manager
         */
        $query = $this->getTableLocator()->get('employee_title')
        ->find()
        ->select([
            'dema.dept_no',
            'em.first_name',
            'em.last_name',
        ])
        ->join([
            'em' => [
                'table' => 'employees',
                'conditions' => 'em.emp_no = employee_title.emp_no'
            ],
            'dema' => [
                'table' => 'dept_manager',
                'conditions' => 'dema.emp_no = em.emp_no'
            ]
        ])
        ->where([
            'employee_title.to_date' => '9999-01-01',
            'employee_title.title_no' => '7',
            'dema.dept_no' => $id
        ]);

        if (!is_null($query->first())) {
            $manager = $query->first()->em['first_name'] . ' ' . $query->first()->em['last_name'];
        } else {
            $manager = null;
        }

        // Assignation des variables pour la vue
        $department
            ->set('nbVacants', $nbVacants)
            ->set('nbEmpl', $nbEmpl)
            ->set('picture', $picture)
            ->set('manager_name', $manager);

        $this->set(compact('department'));
    }
}
