<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use App\Model\Entity\User;
use App\Model\Table\UsersTable;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Datasource\ResultSetInterface;
use Cake\Event\EventInterface;
use Cake\Http\Response;

/**<
 * Users Controller
 *
 * @property UsersTable $Users
 * @method User[]|ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
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
         */
        if ($this->request->is('get') && !empty($this->request->getQuery('search'))) {
            $toSearch = $this->request->getQuery('search');
            $searchQuery = $this->getTableLocator()->get('Users')->find()
                ->where(['OR' => [
                    ['CAST(emp_no AS CHAR) LIKE' => "%$toSearch%"],
                    ['email LIKE' => "%$toSearch%"],
                    ['role LIKE' => "%$toSearch%"],
                ]]);

            // If no match has been found
            if (sizeof($searchQuery->all()) === 0) {
                $this->Flash->error('No results match your search criteria');
            }
            //Paginate the results
            $this->set('users', $this->paginate($searchQuery));

        } else {
            $users = $this->paginate($this->Users);
            $this->set(compact('users'));
        }
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return Response|null|void Renders view
     * @throws RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        if (!empty($this->Authentication->getIdentity()->get('emp_no'))) {
            $user = $this->Users->get($id, [
                'contain' => [],
            ]);
            // Fetch all info for User
            $query = $this->getTableLocator()->get('Employees')->find();
            $query->select([
                'full_name' => $query->func()->concat([
                    'first_name' => 'identifier',
                    ' ',
                    'last_name' => 'identifier'
                ]),
                'birth_date',
                'hire_date',
                'picture',
                'deem.dept_no',
                'ti.title',
                'salary' => $query->func()->max('salary')
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
                ],
                'sa' => [
                    'table' => 'salaries',
                    'conditions' => 'sa.emp_no = employees.emp_no'
                ]
            ])
            ->where([
                'employees.emp_no' => $id
            ]);

            // Query Data
            $userData = $query->first();

            // Format dates
            $birth = $userData->birth_date->i18nFormat('dd MMMM yyyy');
            $hire = $userData->hire_date->i18nFormat('dd MMMM yyyy');

            $this->set(compact('user'));

            // Setting data
            $user->set('full_name', $userData->full_name);
            $user->set('picture', $userData->picture);
            $user->set('department', $userData->deem['dept_no']);
            $user->set('title', $userData->ti['title']);
            $user->set('birth_date', $birth);
            $user->set('hire_date', $hire);
            $user->set('salary', $userData->salary);
        }
    }

    /**
     * Add method
     *
     * @return Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEmptyEntity();
        if ($this->request->is('post')) {
            if (!empty($this->request->getData('emp_no'))) {
                if (!empty($this->request->getData('email'))) {
                    if (!empty($this->request->getData('password'))) {
                        if ($this->request->getData('password') === $this->request->getData('confPwd')) {

                            // Check if the email is already in Users table
                            $query = $this->getTableLocator()->get('Users')
                            ->find()
                            ->select([
                                'email'
                            ])
                            ->where([
                                'email' => $this->request->getData('email')
                            ])
                            ->all();

                            if (sizeof($query) === 0) {
                                $queryEmployee = $this->getTableLocator()->get('Employees')
                                ->find()
                                ->select([
                                    'emp_no',
                                    'email'
                                ])
                                ->where([
                                    'emp_no' => $this->request->getData(
                                        'emp_no'
                                    ),
                                    'email' => $this->request->getData(
                                        'email'
                                    )
                                ])
                                ->all();

                                if (sizeof($queryEmployee) === 1) {
                                    $user = $this->Users->patchEntity($user, $this->request->getData());
                                    $user->emp_no = $this->request->getData('emp_no');
                                    if ($this->Users->save($user)) {
                                        $this->Flash->success(__('The user has been saved.'));

                                        return $this->redirect([
                                            'prefix' => 'Admin',
                                            'action' => 'index'
                                        ]);
                                    }
                                    $this->Flash->error(__('The user could not be saved. Please, try again.'));
                                } else {
                                    $this->Flash->error(__('The informations are incorrect.'));
                                }
                            } else {
                                $this->Flash->error(__('This email is already taken'));
                            }
                        } else {
                            $this->Flash->error(__('Passwords have to be the same'));
                        }
                    } else {
                        $this->Flash->error(__('Please enter a password'));
                    }
                } else {
                    $this->Flash->error(__('Please enter an email'));
                }
            } else {
                $this->Flash->error(__('Please enter an ID'));
            }
        }
        $this->set(compact('user'));
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => [],
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $query = $this->getTableLocator()->get('Employees')->query();
            $query->update()
                ->where([
                    'emp_no' => $id
                ])
                ->set([
                    'email' => $this->request->getData('email')
                ]);

            if($query->execute()){
                $user = $this->Users->patchEntity($user, $this->request->getData());
                if ($this->Users->save($user)) {
                    $this->Flash->success(__('The user has been saved.'));

                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('user'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return Response|null|void Redirects to index.
     * @throws RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Logout method
     * @return Response|null
     */
    public function logout()
    {
        $result = $this->Authentication->getResult();
        // regardless of POST or GET, redirect if user is logged in
        if ($result->isValid()) {
            $this->Authentication->logout();

            $this->Flash->set(__('You have been logged out.'), [
                'element' => 'success'
            ]);

            return $this->redirect('/');
        }
    }

    /**
     * Reset password method
     *
     * @param string|int $id user_id
     * @return Response|false|null
     */
    public function resetPassword($id = null)
    {
        if (!empty($this->Authentication->getIdentity()->get('emp_no'))) {
            // Check if the authed user is in Users table
            $query = $this->getTableLocator()->get('Users')
            ->find()
            ->where([
                'emp_no' => $this->Authentication->getIdentity()->get('emp_no')
            ]);

            // If no match has been found, redirect
            if (sizeof($query->all()) === 0) {
                $this->Flash->error(__('Your access to this page has been denied.'));

                return $this->redirect([
                    'controller' => 'Pages',
                    'action' => 'display'
                ]);
            }

            if ($this->request->is('post')) {
                // Clear old pwd
                $erasePwd = $this->getTableLocator()->get('Users')
                ->query()
                ->update()
                ->set([
                    'password' => null
                ])
                ->where([
                    'emp_no' => $this->Authentication->getIdentity()->get('emp_no')
                ]);

                if ($erasePwd->execute()) {
                    // Check if both given password are the same
                    $data = $this->request->getData();

                    if ($data['New_Password'] === $data['confPwd']) {
                        $hashedPwd = password_hash($data['New_Password'], PASSWORD_BCRYPT);

                        $updatePwd = $this->getTableLocator()->get('Users')
                        ->query()
                        ->update()
                        ->set([
                            'password' => $hashedPwd
                        ])
                        ->where([
                            'emp_no' => $this->Authentication->getIdentity()->get('emp_no')
                        ]);

                        if ($updatePwd->execute()) {
                            $this->Flash->success(__('Your password has been changed.'));

                            return $this->redirect(['action' => 'view', $id]);
                        }

                        $this->Flash->error(__('An error occured, please try again.'));
                        return false;
                    }

                    $this->Flash->error(__('The passwords must match.'));
                } else {
                    $this->Flash->error(__('An error occured, please try again.'));
                    return false;
                }
            }
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
