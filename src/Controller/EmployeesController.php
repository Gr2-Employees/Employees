<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Entity\Employee;
use App\Model\Table\EmployeesTable;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Datasource\ResultSetInterface;
use Cake\Event\EventInterface;
use Cake\Http\Response;
use Cake\View\CellTrait;
use DateTime;
use Exception;

/**
 * Employees Controller
 *
 * @property EmployeesTable $Employees
 * @method Employee[]|ResultSetInterface paginate($object = null, array $settings = [])
 */
class EmployeesController extends AppController
{
    use CellTrait;

    /**
     * Index method
     *
     * @return Response|null|void Renders view
     */
    public function index()
    {
        /**
         * Search function
         */
        if ($this->request->is('get') && !empty($this->request->getQuery('search'))) {
            $toSearch = $this->request->getQuery('search');

            if(strlen($toSearch) < 2) {
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
     * @throws RecordNotFoundException|Exception When record not found.
     */
    public function view($id = null)
    {
        $employee = $this->Employees->get($id, [
            'contain' => ['salaries', 'employee_title'],
        ]);

        $titles = $employee->employee_title;

        $today = new DateTime();

        foreach ($titles as $title) {
            $date = new DateTime($title->to_date->format('Y-m-d'));

            if ($date > $today) {
                $employee->fonction = $title->title_no;

            } else if ($date < $today) {
                $employee->fonction = $title->title_no;
            }
        }

        $query = $this->getTableLocator()->get('titles')
        ->find()
        ->select([
            'titles.title',
        ])
        ->join([
            'e' => [
                'table' => 'employee_title',
                'conditions' => 'e.title_no = titles.title_no'
            ]
        ])
        ->where([
            'titles.title_no' => $employee->fonction,
            'e.emp_no' => $id
        ]);

        $employee->set('fonction', $query->first()['title']);

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

        //Envoyer vers la vue
        $this->set(compact('employee'));
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
            'contain' => [],
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $employee = $this->Employees->patchEntity($employee, $this->request->getData());

            if ($this->Employees->save($employee)) {
                $this->Flash->success(__('The employee has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The employee could not be saved. Please, try again.'));
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
        //Sécurité
        $this->request->allowMethod(['post', 'delete']);

        //Récupérer
        $employee = $this->Employees->get($id);

        //Traitement
        if ($this->Employees->delete($employee)) {
            $this->Flash->success(__('The employee has been deleted.'));
        } else {
            $this->Flash->error(__('The employee could not be deleted. Please, try again.'));
        }

        //Envoyer vers la vue: NON => Redirection
        return $this->redirect(['action' => 'index']);
    }
    public function beforeFilter($event)
    {
        parent::beforeFilter($event);

        if ($this->Authentication->getIdentity() === null){
            return $this->redirect([
                'controller' => 'Pages',
                'action' => 'display'
            ]);
        }
    }
}
