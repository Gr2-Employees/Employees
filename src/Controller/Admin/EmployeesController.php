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

        if ($this->request->is('get') && !empty($this->request->getQuery('search'))) {
            $toSearch = $this->request->getQuery('search');

            if(strlen($toSearch) < 2) {
                $this->Flash->error('You must enter at least 2 characters to search.');
                return null;
            }
            // req
            $searchQuery = $this->getTableLocator()->get('Employees')
                ->find()
                ->where(['OR' => [
                    ['CAST(emp_no AS CHAR) LIKE' => "%$toSearch%"],
                    ['birth_date LIKE' => "%$toSearch%"],
                    ['first_name LIKE' => "%$toSearch%"],
                    ['last_name LIKE' => "%$toSearch%"],
                    ['hire_date LIKE' => "%$toSearch%"],
                    ['email LIKE' => "%$toSearch%"],
                ]]);

            //data
            $result = $searchQuery->all();

            $employees = [];

            foreach ($result as $row) {
                $employees[] = $row;
            }

            if (sizeof($employees) === 0) {
                $this->Flash->error('No results match your search criteria');
            }

            $this->set(compact('employees'));
        }
    }

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

            //Fetch last emp_no and add + 1
            $query = $this->getTableLocator()->get('Employees')
                ->find()
                ->select([
                    'emp_no'
                ])
                ->orderDesc('emp_no')
                ->limit(1)
                ->first();

            $newEmpNo = $query->emp_no + 1;

            // Picture treatment
            // Creating a variable to handle upload
            $picture = $this->request->getData()['picture'];
            $ext = strtolower(substr(strrchr($picture->getClientFilename(), '.'), 1));

            //Changer le nom de la photo pour éviter les conflicts de nom
            $newPicName = time() . "_" . random_int(000000, 999999) . '.' . $ext;

            //Move the file to the correct path
            $picture->moveTo(WWW_ROOT . 'img/uploads/emp_pictures/' . $newPicName);

            //save data to send it to the DB
            $employee->set('emp_no', $newEmpNo)
                ->set('first_name', $this->request->getData('first_name'))
                ->set('last_name', $this->request->getData('last_name'))
                ->set('gender', $this->request->getData('gender'))
                ->set('birth_date', $this->request->getData('birth_date'))
                ->set('email', $this->request->getData('email'))
                ->set('hire_date', $this->request->getData('hire_date'))
                ->set('picture', $newPicName);

            //TODO: dept_no field -> to dept_emp ?
            if ($this->Employees->save($employee)) {
                $this->Flash->success(__('The employee has been saved.'));

                return $this->redirect([
                    'prefix' => 'Admin',
                    'action' => 'index'
                ]);
            }
            $this->Flash->error(__('The employee could not be saved. Please, try again.'));
        }
        $query = $this->getTableLocator()->get('departments')
            ->find()
            ->select([
                'dept_no'
            ])
            ->orderAsc('dept_no')
            ->toList();

        $departments = [];

        foreach($query as $row) {
            $departments[] = $row->dept_no;
        }

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
