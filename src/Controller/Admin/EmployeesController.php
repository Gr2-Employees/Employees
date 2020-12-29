<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use App\Model\Entity\Employee;
use App\Model\Table\EmployeesTable;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Datasource\ResultSetInterface;
use Cake\Http\Response;

/**
 * Employees Controller
 *
 * @property EmployeesTable $Employees
 * @method Employee[]|ResultSetInterface paginate($object = null, array $settings = [])
 */
class EmployeesController extends AppController
{
    /**
     * Index method
     *
     * @return Response|null|void Renders view
     */
    public function index()
    {
        $employees = $this->paginate($this->Employees);

        $this->set(compact('employees'));

        if ($this->request->is('post')) {
            if (!empty($this->request->getData('search'))) {
                $toSearch = $this->request->getData('search');
                // req
                $searchQuery = $this->getTableLocator()->get('Employees')->find()
                    ->where(['OR' => [
                        ['CAST(emp_no AS CHAR) LIKE' => "%$toSearch%"],
                        ['birth_date LIKE' => "%$toSearch%"],
                        ['first_name LIKE' => "%$toSearch%"],
                        ['last_name LIKE' => "%$toSearch%"],
                        ['gender LIKE' => "%$toSearch%"],
                        ['hire_date LIKE' => "%$toSearch%"],
                        ['email LIKE' => "%$toSearch%"],
                    ]]);

                //data
                $result = $searchQuery->all();
                $employees = [];
                foreach ($result as $row) {
                    $employees[] = $row;
                }
                if(empty($employees)){
                    $this->Flash->error('No results match your search criteria');
                }
                $this->set(compact('employees'));
            }
        }
    }

    /*public function search() {
        $this->disableAutoRender();
        if ($this->request->is('post')) {
            $toSearch = (int)$this->request->getData('search');

            // req
            $searchQuery = $this->getTableLocator()->get('Employees')
                ->find()
                ->select([
                    'emp_no'
                ])
                ->where(['CAST(emp_no AS CHAR) LIKE' => "%$toSearch%"]);

            //data
            $result = $searchQuery->all();
            foreach($result as $row){
                $employee[] = $row;
            }


            $this->viewBuilder()->setLayout('index');
            // $employees = $data
        }
    }*/

    /**
     * View method
     *
     * @param string|null $id Employee id.
     * @return Response|null|void Renders view
     * @throws RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $employee = $this->Employees->get($id, [
            'contain' => ['salaries', 'employee_title'],
        ]);

        $this->set(compact('employee'));
    }

    /**
     * Add method
     *
     * @return Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $employee = $this->Employees->newEmptyEntity();
        if ($this->request->is('post')) {
            $employee = $this->Employees->patchEntity($employee, $this->request->getData());
            if ($this->Employees->save($employee)) {
                $this->Flash->success(__('The employee has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The employee could not be saved. Please, try again.'));
        }
        $departments = $this->Employees->Departments->find('list', ['limit' => 200]);
        $this->set(compact('employee', 'departments'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Employee id.
     * @return Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $employee = $this->Employees->get($id, [
            'contain' => ['Departments'],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $employee = $this->Employees->patchEntity($employee, $this->request->getData());
            if ($this->Employees->save($employee)) {
                $this->Flash->success(__('The employee has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The employee could not be saved. Please, try again.'));
        }
        $departments = $this->Employees->Departments->find('list', ['limit' => 200]);
        $this->set(compact('employee', 'departments'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Employee id.
     * @return Response|null|void Redirects to index.
     * @throws RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $employee = $this->Employees->get($id);
        if ($this->Employees->delete($employee)) {
            $this->Flash->success(__('The employee has been deleted.'));
        } else {
            $this->Flash->error(__('The employee could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
