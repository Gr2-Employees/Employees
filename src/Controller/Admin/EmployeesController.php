<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use App\Model\Entity\Employee;
use App\Model\Table\EmployeesTable;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Datasource\ResultSetInterface;
use Cake\Http\Response;
use Exception;

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
        /**
         * Search function
         * The given input has to be at least 3 characters long to begin searching
         */
        if ($this->request->is('get') && !empty($this->request->getQuery('search'))) {
            $toSearch = $this->request->getQuery('search');

            if (strlen($toSearch) < 2) {
                $this->Flash->error('You must enter at least 2 characters to search.');
                return null;
            }

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

            // If no match has been found
            if (sizeof($searchQuery->all()) === 0) {
                $this->Flash->error('No results match your search criteria');
            }
            //Paginate the results
            $this->set('employees', $this->paginate($searchQuery));

        } else {
            $employees = $this->paginate($this->Employees);
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
     * @throws Exception
     */
    public function add()
    {
        $employee = $this->Employees->newEmptyEntity();

        /**
         * Fetch departments list to populate department select in add form
         */
        $query = $this->getTableLocator()->get('departments')
        ->find()
        ->select([
            'dept_no'
        ])
        ->orderAsc('dept_no');

        $departments = [];

        foreach ($query as $row) {
            $departments[] = $row->dept_no;
        }

        /**
         * Fetch titles list to populate title select in add form
         */
        $query = $this->getTableLocator()->get('titles')
        ->find()
        ->select([
            'title',
            'title_no'
        ])
        ->where([
            'title !=' => 'Chômeur'
        ])
        ->toArray();

        $titles = [];

        foreach ($query as $row) {
            $titles[$row->title_no] = $row->title;
        }

        if ($this->request->is('post')) {
            // Fetch last emp_no and add + 1
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

            // Change the name of the picture to avoid name conflicts
            $newPicName = time() . "_" . random_int(000000, 999999) . '.' . $ext;

            // Move the file to the correct path
            $picture->moveTo(WWW_ROOT . 'img/uploads/emp_pictures/' . $newPicName);

            // Save data to send it to the DB
            $employee->set('emp_no', $newEmpNo)
            ->set('first_name', $this->request->getData('first_name'))
            ->set('last_name', $this->request->getData('last_name'))
            ->set('gender', $this->request->getData('gender'))
            ->set('birth_date', $this->request->getData('birth_date'))
            ->set('email', $this->request->getData('email'))
            ->set('hire_date', $this->request->getData('hire_date'))
            ->set('picture', $newPicName);

            // Save dept_no and title_no values from form's select fields
            $deptValue = $departments[$this->request->getData('dept_no')];
            $titleValue = $this->request->getData('title');

            if ($this->Employees->save($employee)) {
                $insertDeem = $this->getTableLocator()->get('dept_emp')->query();
                $insertDeem->insert(['emp_no', 'dept_no', 'from_date', 'to_date'])
                ->values([
                    'emp_no' => $newEmpNo,
                    'dept_no' => $deptValue,
                    'from_date' => $insertDeem->func()->now(),
                    'to_date' => '9999-01-01'
                ]);

                if ($insertDeem->execute()) {
                    $insertEmpT = $this->getTableLocator()->get('employee_title')->query();
                    $insertEmpT->insert(['emp_no', 'title_no', 'from_date', 'to_date'])
                    ->values([
                        'emp_no' => $newEmpNo,
                        'title_no' => $titleValue,
                        'from_date' => $insertEmpT->func()->now(),
                        'to_date' => '9999-01-01'
                    ]);

                    if ($insertEmpT->execute()) {
                        $this->Flash->success(__('The employee has been saved.'));

                        return $this->redirect([
                            'prefix' => 'Admin',
                            'action' => 'index'
                        ]);
                    }

                    $this->Flash->error(__('The employee could not be saved. Please, try again.'));
                } else {
                    $this->Flash->error(__('The employee could not be saved. Please, try again.'));
                }
            } else {
                $this->Flash->error(__('The employee could not be saved. Please, try again.'));
            }
        }

        $this->set(compact('employee', 'departments', 'titles'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Employee id.
     * @return Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws RecordNotFoundException|Exception When record not found.
     */
    public function edit($id = null)
    {
        $employee = $this->Employees->get($id, [
            'contain' => [],
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            if (!empty($this->request->getData()['picture']->getClientFilename())) {

                // Picture treatment
                // Creating a variable to handle upload
                $picture = $this->request->getData()['picture'];
                $ext = strtolower(substr(strrchr($picture->getClientFilename(), '.'), 1));

                // Change the name of the picture to avoid name conflicts
                $newPicName = time() . "_" . random_int(000000, 999999) . '.' . $ext;

                // Move the file to the correct path
                $picture->moveTo(WWW_ROOT . 'img/uploads/emp_pictures/' . $newPicName);

                if (!is_null($employee->picture)) {
                    // Suppresion de l'ancienne image
                    $oldPicDirectory = WWW_ROOT . 'img/uploads/emp_pictures/' . $employee->picture;
                    unlink($oldPicDirectory);
                }

                // Save new picture to send it to the DB
                $employee->picture = $newPicName;
            }

            $employee->first_name = $this->request->getData('first_name');
            $employee->last_name = $this->request->getData('last_name');
            $employee->gender = $this->request->getData('gender');
            $employee->birth_date = $this->request->getData('birth_date');
            $employee->email = $this->request->getData('email');
            $employee->hire_date = $this->request->getData('hire_date');

            $query = $this->getTableLocator()->get('Users')->query();
            $query->update()
                ->where([
                    'emp_no' => $id
                ])
                ->set([
                    'email' => $this->request->getData('email')
                ]);
            if ($query->execute()) {
                if ($this->Employees->save($employee)) {
                    $this->Flash->success(__('The employee has been saved.'));

                    return $this->redirect([
                        'action' => 'index'
                    ]);
                }
                $this->Flash->error(__('The employee could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('employee'));
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
