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
         * Recherche du nombre total d'employés dans un département
         */

        // Selects the table
        $query = $this->getTableLocator()->get('dept_emp')->find();

        // Selected fields for the query
        $query->select([
            'count' => $query->func()->count('*')
        ]);

        // Where clause
        $query->where([
            'dept_emp.dept_no' => $id
        ]);

        // Fetches the result
        $nbEmpl = $query->first()->count;

        /**
         * Nombres de postes vacants
         *  -> Récupérer le dept_no
         *  -> Fetch le résultat
         */
        $query = $this->getTableLocator()->get('vacancies')
            ->find()
            ->select([
                'quantity'
            ])
            ->where([
                'dept_no' => $id
            ])
            ->first();

        if (is_null($query)) {
            // If no vacant spot
            $nbVacants = 0;
        } else {
            $nbVacants = $query->quantity;
        }

        /**
         * Récupération de la photo du manager
         */
        $query = $this->getTableLocator()->get('dept_manager')->find();

        $query->select([
            'dept_manager.picture'
        ]);

        $query->where([
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

        $manager = $query->first()->em['first_name'] . ' ' . $query->first()->em['last_name'];

        /**
         * Get how long the manager has been on that role
         */
        $query = $this->getTableLocator()->get('dept_manager')->find()
            ->select([
                'now' => $query->func()->now(),
                'dept_manager.from_date'
            ])
            ->where([
                'dept_manager.to_date' => '9999-01-01',
                'dept_manager.dept_no' => $id
            ]);

        $result = $query->first();

        $interval = date_diff($result->now, $result->from_date);
        $since = $interval->format('%d day(s) %m month(s) %y year(s)');

        /**
         * Get dept's average salary
         */

        //Subquery
        $managerQuery = $this->getTableLocator()->get('dept_manager')->find()
            ->select([
                'dept_manager.emp_no'
            ])
            ->where([
                'dept_manager.dept_no' => $id,
                'dept_manager.to_date' => '9999-01-01'
            ]);

        // Main query
        $query = $this->getTableLocator()->get('salaries')->find();
        $query->select([
            'average' => $query->func()->avg('salary')
        ])
        ->join([
            'deem' => [
                'table' => 'dept_emp',
                'conditions' => 'deem.emp_no = salaries.emp_no'
            ]
        ])
        ->where([
            'deem.dept_no' => $id,
            'deem.emp_no NOT IN' => $managerQuery
        ]);

        $averageSalary = $query->first()->average;

        // Assignation des variables pour la vue
        $department
            ->set('nbVacants', $nbVacants)
            ->set('nbEmpl', $nbEmpl)
            ->set('picture', $picture)
            ->set('manager_name', $manager)
            ->set('since', $since)
            ->set('averageSalary', $averageSalary);

        $this->set(compact('department'));
    }

    public function showEmp($id = null)
    {
        $department = $this->Departments->get($id, [
            'contain' => [],
        ]);

        $query = $this->getTableLocator()->get('dept_emp')
            ->findAllByDeptNo($department->dept_no)
            ->select([
                'em.emp_no',
                'em.birth_date',
                'em.first_name',
                'em.last_name',
                'em.gender',
                'em.hire_date',
                'dept_emp.from_date',
                'dept_emp.to_date'
            ])
            ->join([
                'em' => [
                    'table' => 'employees',
                    'conditions' => 'dept_emp.emp_no = em.emp_no'
                ]
            ])
            ->where([
                'dept_emp.to_date' => '9999-01-01'
            ])
            ->limit(255);

        $employees = $query->all();

        $this->set(compact('employees'));
    }

    /**
     * Add method
     *
     * @return Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {

        $department = $this->Departments->newEmptyEntity();
        if ($this->request->is('post')) {

            //Format the Id department into (d + number(3))
            $query = $this->Departments->find('all', ['order' => ['dept_no' => 'DESC']])->limit(1)->first();
            $depNumber = sprintf('%03d', (intval(substr($query->dept_no, 1)) + 1));
            $uniqueId = 'd' . $depNumber;
            $department->set('dept_no', $uniqueId);

            // Creating a variable to handle upload
            $picture = $this->request->getData()['picture'];
            $ext = substr(strtolower(strrchr($picture->getClientFilename(), '.')), 1);

            //Changer le nom de la photo pour éviter les conflicts de nom
            $newPicName = time() . "_" . rand(000000, 999999) . '.' . $ext;

            //Move the file to the correct path
            $picture->moveTo(WWW_ROOT . 'img/uploads/dept_pictures/' . $newPicName);

            //save data to send it to the DB
            $department->picture = $newPicName;
            $department->rules = $this->request->getData('rules');
            $department->address = $this->request->getData('address');
            $department->description = $this->request->getData('description');
            $department->dept_name = $this->request->getData('dept_name');

            //save all department data
            if ($this->Departments->save($department)) {
                $this->Flash->success(__('The department has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The department could not be saved. Please, try again.'));
        }
        $this->set(compact('department'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Department id.
     * @return Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $department = $this->Departments->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $department = $this->Departments->patchEntity($department, $this->request->getData());
            if ($this->Departments->save($department)) {
                $this->Flash->success(__('The department has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The department could not be saved. Please, try again.'));
        }

        $this->set(compact('department'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Department id.
     * @return Response|null|void Redirects to index.
     * @throws RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $department = $this->Departments->get($id);
        if ($this->Departments->delete($department)) {
            $this->Flash->success(__('The department has been deleted.'));
        } else {
            $this->Flash->error(__('The department could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
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
