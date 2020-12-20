<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Event\EventInterface;

/**
 * Departments Controller
 *
 * @property \App\Model\Table\DepartmentsTable $Departments
 * @method \App\Model\Entity\Department[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class DepartmentsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
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
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
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

        // Assignation des variables pour la vue
        $department
            ->set('nbVacants', $nbVacants)
            ->set('nbEmpl', $nbEmpl)
            ->set('picture', $picture)
            ->set('manager_name', $manager);

        $this->set(compact('department'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        if ($this->Authentication->getIdentity()['role'] === 'admin') {
            $this->Authorization->skipAuthorization();
        }

        $department = $this->Departments->newEmptyEntity();
        if ($this->request->is('post')) {
            $department = $this->Departments->patchEntity($department, $this->request->getData());
            if ($this->Departments->save($department)) {
                $this->Flash->success(__('The department has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The department could not be saved. Please, try again.'));
        }
        $employees = $this->Departments->Employees->find('list', ['limit' => 200]);
        $this->set(compact('department', 'employees'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Department id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        if ($this->Authentication->getIdentity()['role'] === 'admin') {
            $this->Authorization->skipAuthorization();
        }
        $department = $this->Departments->get($id, [
            'contain' => ['Employees'],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $department = $this->Departments->patchEntity($department, $this->request->getData());
            if ($this->Departments->save($department)) {
                $this->Flash->success(__('The department has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The department could not be saved. Please, try again.'));
        }
        $employees = $this->Departments->Employees->find('list', ['limit' => 200]);
        $this->set(compact('department', 'employees'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Department id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        if ($this->Authentication->getIdentity()['role'] === 'admin') {
            $this->Authorization->skipAuthorization();
        }
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
