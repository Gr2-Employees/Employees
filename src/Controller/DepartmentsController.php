<?php
declare(strict_types=1);

namespace App\Controller;

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
         *  -> Utiliser le nombre d'employés par département ($nbEmpl)
         *  -> Requête qui compte les postes ayant pour date '9999-01-01' (date 'actuelle') en fonction du département
         *  -> Différence de $nbEmpl avec le résultat obtenu
         */

        $query = $this->getTableLocator()->get('dept_emp')->find();

        $query->select([
            'count' => $query->func()->count('*')
        ]);

        $query->where([
            'dept_emp.dept_no' => $id,
            'to_date' => '9999-01-01'
        ]);

        $nbToDate = $query->first()->count;

        // Nombre postes vacants = $nbEmpl - $nbToDate
        $nbVacants = $nbEmpl - $nbToDate;

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
        $department = $this->Departments->newEmptyEntity();
        if ($this->request->is('post')) {
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
     * Edit method
     *
     * @param string|null $id Department id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
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
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
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
}
