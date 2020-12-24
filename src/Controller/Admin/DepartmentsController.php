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
use Exception;

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
        $query = $this->getTableLocator()->get('dept_emp')->find();

        $query->select([
            'count' => $query->func()->count('*')
        ]);

        $query->where([
            'dept_emp.dept_no' => $id
        ]);

        $nbEmpl = $query->first()->count;

        /**
         * Nombres de postes vacants
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

        if (!is_null($query->first())) {
            $pictureManager = $query->first()->picture;
        } else {
            $pictureManager = null;
        }

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

        if ($result !== NULL) {
            $interval = date_diff($result->now, $result->from_date);
            $since = $interval->format('%y year(s) %m month(s) %d day(s)');
        } else {
            $since = 0;
        }

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
            ->set('pictureManager', $pictureManager)
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
                'dept_emp.to_date',
                'dept_emp.dept_no'
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
     * @throws Exception
     */
    public function add(): ?Response
    {
        $department = $this->Departments->newEmptyEntity();
        if ($this->request->is('post')) {

            //Format the Id department into (d + number(3))
            $query = $this->Departments->find('all', ['order' => ['dept_no' => 'DESC']])->limit(1)->first();
            $depNumber = sprintf('%03d', ((int)substr($query->dept_no, 1) + 1));
            $uniqueId = 'd' . $depNumber;
            $department->set('dept_no', $uniqueId);

            // Creating a variable to handle upload
            $picture = $this->request->getData()['picture'];
            $ext = strtolower(substr(strrchr($picture->getClientFilename(), '.'), 1));

            //Changer le nom de la photo pour éviter les conflicts de nom
            $newPicName = time() . "_" . random_int(000000, 999999) . '.' . $ext;

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
    public function edit($id = null): ?Response
    {
        $department = $this->Departments->get($id, [
            'contain' => [],
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            if(isset($_POST['picture'])){
                // Creating a variable to handle upload
                $picture = $this->request->getData()['picture'];

                //Changer le nom de la photo pour éviter les conflicts de nom
                $ext = strtolower(substr(strrchr($picture->getClientFilename(), '.'), 1));
                $newPicName = time() . "_" . rand(000000, 999999) . '.' . $ext;

                //Move the file to the correct path
                $picture->moveTo(WWW_ROOT . 'img/uploads/dept_pictures/' . $newPicName);

                //Suppresion de l'image ancienne
                $oldPicDirectory = WWW_ROOT .'img/uploads/dept_pictures/' . $department->picture;
                unlink($oldPicDirectory);

                //save new picture to send it to the DB
                $department->picture = $newPicName;
            }

            $department->rules = $this->request->getData('rules');
            $department->address = $this->request->getData('address');
            $department->description = $this->request->getData('description');
            $department->dept_name = $this->request->getData('dept_name');

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
    public function delete($id = null): ?Response
    {
        $this->request->allowMethod(['post', 'delete']);
        $department = $this->Departments->get($id);

        $query = $this->getTableLocator()->get('dept_emp')->find();
        $query->select([
            'nbEmpl' => $query->func()->count('emp_no')
            ])
            ->where([
                'dept_no' => $id,
                'to_date' => '9999-01-01'
        ]);

        // If amount of employees in dept = 0
        if ($query->first()->nbEmpl === 0) {
            if ($this->Departments->delete($department)) {
                $this->Flash->success(__('The department has been deleted.'));
            } else {
                $this->Flash->error(__('The department could not be deleted. Please, try again.'));
            }
        } else {
            $this->Flash->error(__('You mustn\'t delete a department that has employees.'));

            return $this->redirect([
                'action' => 'view',
                $id
            ]);
        }

        return $this->redirect(['action' => 'index']);
    }

    public function revokeManager($id = null): ?Response
    {
        if ($id === null) {
            $this->Flash->error(__('There has to be a department ID to access this functionality.'));

            return $this->redirect([
                'prefix' => 'Admin',
                'action' => 'index'
            ]);
        }

        $this->request->allowMethod(['post', 'delete']);
        $query = $this->getTableLocator()->get('dept_manager')->query();
        $query->update()
            ->set([
                'to_date' => $query->func()->now(),
            ])
            ->where([
                'dept_no' => $id,
                'to_date' => '9999-01-01'
        ]);

        if ($query->execute()) {


            // Get old's manager emp_no to update employee_title
            $query = $this->getTableLocator()->get('employees')
                ->find()
                ->select([
                    'emplId' => 'employees.emp_no'
                ])
                ->join([
                    'dema' => [
                        'table' => 'dept_manager',
                        'conditions' => 'dema.emp_no = employees.emp_no'
                    ],
                    'emti' => [
                        'table' => 'employee_title',
                        'conditions' => 'emti.emp_no = employees.emp_no'
                    ]
                ])
                ->where([
                    'dema.dept_no' => $id,
                    'emti.to_date' => '9999-01-01',
                    'emti.title_no' => '7'
            ]);

            $oldManagerId = $query->first()->emplId;

            // Update to_date in employee_title
            $query = $this->getTableLocator()->get('employee_title')->query();
            $query->update()
                ->set([
                    'to_date' => $query->func()->now(),
                    'title_no' => '8'
                ])
                ->where([
                    'to_date' => '9999-01-01',
                    'title_no' => '7',
                    'emp_no' => $oldManagerId
            ]);

            if ($query->execute()) {
                // Add vacant manager post in dept
                $query = $this->getTableLocator()->get('Vacancies')->query();
                $query->insert(['dept_no', 'title_no', 'quantity'])
                    ->values([
                        'dept_no' => $id,
                        'title_no' => '7',
                        'quantity' => '1'
                ]);

                if ($query->execute()) {
                    $this->Flash->success(__('The manager has been revoked.'));
                }
            }
        } else {
            $this->Flash->error(__('There was an error when revoking the manager.'));
        }

        return $this->redirect([
            'action' => 'view',
            $id
        ]);
    }

    public function showQualified($id = null): ?Response
    {
        if ($id === null) {
            $this->Flash->error(__('There has to be a department ID to access this functionality.'));

            return $this->redirect([
                'prefix' => 'Admin',
                'action' => 'index'
            ]);
        }

        // Fetch Employees
        $query = $this->getTableLocator()->get('employees')->find();
        $query->select([
            'employees.emp_no',
            'employees.first_name',
            'employees.last_name',
            'employees.gender',
            'employees.hire_date',
            'employees.email',
            'ti.title',
            'deem.dept_no'
        ])
        ->join([
            'deem' => [
                'table' => 'dept_emp',
                'conditions' => 'deem.emp_no = employees.emp_no'
            ],
            'emti' => [
                'table' => 'employee_title',
                'conditions' => 'emti.emp_no = employees.emp_no'
            ],
            'ti' => [
                'table' => 'titles',
                'conditions' => 'ti.title_no = emti.title_no'
            ]
        ])
        ->where([
            'deem.dept_no' => $id,
            'deem.to_date' => '9999-01-01',
            'emti.title_no' => '3'
        ])
        ->limit(25);

        $employees = $query->all();

        // If button Assign has been pressed
        if(!empty($this->request->getQuery())) {
            $dept_no = h($this->request->getQuery('dept'));

            // Verif no manager
            $query = $this->getTableLocator()->get('dept_manager')
                ->find()
                ->where([
                    'to_date' => '9999-01-01',
                    'dept_no' => $dept_no
                ])
            ->first();

            if (!is_null($query)) {

                $this->Flash->error(__('There is a manager in this department.'));

                return $this->redirect([
                    'action' => 'view',
                    $dept_no
                ]);
            }

            $emp_no = h($this->request->getQuery('emp'));

            // Update employee date to end its position
            $update = $this->getTableLocator()->get('employee_title')->query();
            $update->update()
                ->set([
                    'to_date' => $update->func()->now(),
                ])
                ->where([
                    'emp_no' => $emp_no,
                    'to_date' => '9999-01-01'
                ]);

            if ($update->execute()) {
                /**
                 * Add new employee_title row with from_ now() and to_ 9999-01-01
                 * Update employee_title date to now() + set title_no to 7
                 */
                $insert = $this->getTableLocator()->get('employee_title')->query();
                $insert->insert([
                    'emp_no',
                    'title_no',
                    'from_date',
                    'to_date'
                ])
                ->values([
                    'emp_no' => $emp_no,
                    'title_no' => '7',
                    'from_date' => $insert->func()->now(),
                    'to_date' => '9999-01-01'
                ]);

                if ($insert->execute()) {
                    // Add employee to dept_manager
                    $insertToManager = $this->getTableLocator()->get('dept_manager')->query();
                    $insertToManager->insert([
                        'emp_no',
                        'dept_no',
                        'from_date',
                        'to_date',
                        'picture',
                        'email'
                    ])
                    ->values([
                        'emp_no' => $emp_no,
                        'dept_no' => $dept_no,
                        'from_date' => $insertToManager->func()->now(),
                        'to_date' => '9999-01-01',
                        'picture' => 'noUserPic.jpg',
                        'email' => 'abcdef@gmail.com'
                    ]);

                    if ($insertToManager->execute()) {
                        // Delete vacancy for manager position
                        $delete = $this->getTableLocator()->get('vacancies')->query();
                        $delete->delete()
                            ->where([
                                'dept_no' => $dept_no,
                                'title_no' => '7'
                            ]);

                        if ($delete->execute()) {
                            $this->Flash->success(__('Employee n° ' . $emp_no . ' has been promoted to Manager of department n° ' . $dept_no . ' !'));

                            return $this->redirect([
                                'action' => 'view',
                                $dept_no
                            ]);
                        }
                    }
                }
            }
        }
        $this->set(compact('employees'));
    }


    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        if (($this->Authentication->getIdentity() === null) || ($this->Authentication->getIdentity()->role !== 'admin')) {
            $this->Flash->error(__('You cannot access this page.'));

            return $this->redirect('/pages');
        }
    }
}
