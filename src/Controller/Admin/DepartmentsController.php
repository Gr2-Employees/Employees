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
         * Nombre de postes vacants dans le departement
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
         * Récupération de la photo du manager du departement
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
         * Get deptartment's average salary
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


    /**
     * Affiche les informations des employés faisant partie du départment $id
     * @param string $id Department id.
     */
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
    public function add()
    {
        $department = $this->Departments->newEmptyEntity();
        if ($this->request->is('post')) {

            /**
             * Format department's ID into (d + number(3)) -> i.e. d010
             */

            // Get latest dept_no
            $query = $this->Departments->find('all', ['order' => ['dept_no' => 'DESC']])
            ->limit(1)
            ->first();

            // Add 1 to the latest dept_no
            $depNumber = sprintf('%03d', ((int)substr($query->dept_no, 1) + 1));
            $uniqueId = 'd' . $depNumber;

            // Set dept_no
            $department->set('dept_no', $uniqueId);

            // Creating a variable to handle upload
            $picture = $this->request->getData()['picture'];
            $ext = strtolower(substr(strrchr($picture->getClientFilename(), '.'), 1));

            // Changer le nom de la photo pour éviter les conflits de nom
            $newPicName = time() . "_" . random_int(000000, 999999) . '.' . $ext;

            // Move the file to the correct path
            $picture->moveTo(WWW_ROOT . 'img/uploads/dept_pictures/' . $newPicName);

            // Save data to send it to the DB
            $department->picture = $newPicName;
            $department->rules = $this->request->getData('rules');
            $department->address = $this->request->getData('address');
            $department->description = $this->request->getData('description');
            $department->dept_name = $this->request->getData('dept_name');

            // Save all department data
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
     * @throws Exception
     */
    public function edit($id = null)
    {
        $department = $this->Departments->get($id, [
            'contain' => [],
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            if (isset($_POST['picture'])) {
                // Creating a variable to handle upload
                $picture = $this->request->getData()['picture'];

                // Changer le nom de la photo pour éviter les conflicts de nom
                $ext = strtolower(substr(strrchr($picture->getClientFilename(), '.'), 1));
                $newPicName = time() . "_" . random_int(000000, 999999) . '.' . $ext;

                // Move the file to the correct path
                $picture->moveTo(WWW_ROOT . 'img/uploads/dept_pictures/' . $newPicName);

                //Suppresion de l'image ancienne
                $oldPicDirectory = WWW_ROOT . 'img/uploads/dept_pictures/' . $department->picture;
                unlink($oldPicDirectory);

                // Save new picture to send it to the DB
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
    public function delete($id = null)
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

        /**
         * Vérification avant de supprimer le departement
         * L'action pourra se faire que lorsqu'il n'y a aucun employé affecté au département
         */
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

    /**
     * Revoke un manager
     * @param null $id
     * @return Response|null
     */
    public function revokeManager($id = null)
    {
        if ($id === null) {
            $this->Flash->error(__('There has to be a specified department ID to access this functionality.'));

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
            // Get old manager's emp_no to update employee_title
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

            // Update to_date field in employee_title to current time
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
                // Add vacant manager post in dept vacancies
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

        // Redirect to department's view
        return $this->redirect([
            'action' => 'view',
            $id
        ]);
    }

    /**
     * Affiche les employés pouvant être nommés Manager
     * Pour des raisons de performances, seuls les employés étant Senior Staff (3) sont qualifiés.
     * @param null $id
     * @return Response|null
     */
    public function showQualified($id = null)
    {
        if ($id === null) {
            $this->Flash->error(__('There has to be a specified department ID to access this functionality.'));

            return $this->redirect([
                'prefix' => 'Admin',
                'action' => 'index'
            ]);
        }

        // Fetch Qualified Employees
        $query = $this->getTableLocator()->get('employees')->find();
        $query->select([
            'employees.emp_no',
            'employees.first_name',
            'employees.last_name',
            'employees.gender',
            'employees.hire_date',
            'employees.email',
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
        ])
        ->where([
            'deem.dept_no' => $id,
            'deem.to_date' => '9999-01-01',
            'emti.title_no' => '3'
        ])
        ->limit(25);

        $employees = $query->all();
        $this->set(compact('employees'));

        // If button Assign has been clicked
        if (!empty($this->request->getQuery('dept'))) {
            $dept_no = h($this->request->getQuery('dept'));

            // Check if there is no manager currently working in the department
            $query = $this->getTableLocator()->get('dept_manager')
            ->find()
            ->where([
                'to_date' => '9999-01-01',
                'dept_no' => $dept_no
            ])
            ->first();

            // If there is : redirect to department's view
            if (!is_null($query)) {
                $this->Flash->error(__('There is a manager in this department.'));

                return $this->redirect([
                    'action' => 'view',
                    $dept_no
                ]);
            }

            $emp_no = $this->request->getQuery('emp');

            // Update employee's to_date field to end its position
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
                 * Add new employee_title row with from_date set to now() and to_date set to 9999-01-01
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
                    // Add employee in dept_manager table
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
                        } else {
                            $this->Flash->error('There was an error deleted the vacant Manager position.');

                            return $this->redirect([
                                'action' => 'view',
                                $dept_no
                            ]);
                        }
                    } else {
                        $this->Flash->error('There was an error when adding the employee as manager.');

                        return $this->redirect([
                            'action' => 'view',
                            $dept_no
                        ]);
                    }
                } else {
                    $this->Flash->error('There was an error when adding the employee\'s newest title.');

                    return $this->redirect([
                        'action' => 'view',
                        $dept_no
                    ]);
                }
            } else {
                $this->Flash->error('There was an error modifying employee\'s dates.');

                return $this->redirect([
                    'action' => 'view',
                    $dept_no
                ]);
            }
        }

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

            // Search query
            $searchQuery = $this->getTableLocator()->get('employees')->find();

            $searchQuery->select([
                'employees.emp_no',
                'employees.first_name',
                'employees.last_name',
                'employees.gender',
                'employees.hire_date',
                'employees.email',
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
            ])
            ->where([
                'OR' => [
                    ['CAST(employees.emp_no AS CHAR) LIKE' => "%$toSearch%"],
                    ['employees.first_name LIKE' => "%$toSearch%"],
                    ['employees.last_name LIKE' => "%$toSearch%"],
                    ['employees.hire_date LIKE' => "%$toSearch%"],
                ],
                'emti.title_no' => '3',
                'deem.dept_no' => $id,
                'deem.to_date' => '9999-01-01'
            ]);

            $employees = $searchQuery->all();

            // If no result has been found with the given input
            if (sizeof($employees) === 0) {
                $this->Flash->error('No results match your search criteria');
                return null;
            }

            $this->set(compact('employees'));
        }
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
