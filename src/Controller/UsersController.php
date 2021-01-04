<?php


declare(strict_types=1);

namespace App\Controller;

use App\Model\Entity\User;
use App\Model\Table\UsersTable;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Datasource\ResultSetInterface;
use Cake\Event\EventInterface;
use Cake\Http\Response;
/**
 * Users Controller
 *
 * @property UsersTable $Users
 * @method User[]|ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    /**
     * View method
     *
     * @param string|null $id User id.
     * @return Response|null|void Renders view
     * @throws RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => [],
        ]);

        //Vérif user is authenticated
        if (!empty($this->Authentication->getIdentity()->get('emp_no'))) {
            //check if user is the same
            $query = $this->getTableLocator()->get('Users')
                ->find()
                ->where([
                    'emp_no' => $this->Authentication->getIdentity()->get('emp_no')
                ]);

            if (sizeof($query->all()) === 0) {
                $this->Flash->error(__('Your access to this page has been denied.'));

                return $this->redirect([
                    'controller' => 'Pages',
                    'action' => 'display'
                ]);
            }

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

            // Setting data
            $user->set('full_name', $userData->full_name);
            $user->set('department', $userData->deem['dept_no']);
            $user->set('title', $userData->ti['title']);
            $user->set('birth_date', $birth);
            $user->set('hire_date', $hire);
            $user->set('salary', $userData->salary);
        }

        $this->set(compact('user'));
    }

    public function resetPassword($id = null)
    {
        if (!empty($this->Authentication->getIdentity()->get('emp_no'))) {
            $query = $this->getTableLocator()->get('Users')
                ->find()
                ->where([
                    'emp_no' => $this->Authentication->getIdentity()->get('emp_no')
                ]);

            if (sizeof($query->all()) === 0) {
                $this->Flash->error(__('Your access to this page has been denied.'));

                return $this->redirect([
                    'controller' => 'Pages',
                    'action' => 'display'
                ]);
            }

            if ($this->request->is('post')) {
                //clear old pwd
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
                    // verif same pwd
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


    /**
     * Add method
     *
     * @return Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function signup()
    {
        $user = $this->Users->newEmptyEntity();
        if ($this->request->is('post')) {
            if (!empty($this->request->getData('emp_no'))) {

                if (!empty($this->request->getData('email'))) {
                    if (!empty($this->request->getData('password'))) {
                        if ($this->request->getData('password') === $this->request->getData('confPwd')) {

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
                                            'action' => 'login'
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
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
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

    public function login()
    {
        $this->request->allowMethod(['get', 'post']);
        $result = $this->Authentication->getResult();
        // regardless of POST or GET, redirect if user is logged in
        if ($result->isValid()) {

            // redirect to /articles after login success
            $redirect = $this->request->getQuery('redirect', [
                'controller' => 'Pages',
                'action' => 'display',
            ]);

            return $this->redirect($redirect);
        }
        // display error if user submitted and authentication failed
        if ($this->request->is('post') && !$result->isValid()) {
            $this->Flash->error(__('Invalid username or password'));
        }
    }

    public function logout()
    {
        $result = $this->Authentication->getResult();
        // regardless of POST or GET, redirect if user is logged in
        if ($result->isValid()) {
            $this->Authentication->logout();

            $this->Flash->set(__('You have been logged out.'), [
                'element' => 'success'
            ]);

            return $this->redirect([
                'controller' => 'Pages',
                'action' => 'display'
            ]);
        }
    }

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        // Configure the login action to not require authentication, preventing
        // the infinite redirect loop issue
        $this->Authentication->addUnauthenticatedActions(['login', 'add']);
    }

}
