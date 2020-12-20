<?php


declare(strict_types=1);

namespace App\Controller;

use App\Model\Entity\User;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Datasource\ResultSetInterface;
use Cake\Event\EventInterface;
use Cake\Http\Response;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
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
        $users = $this->paginate($this->Users);

        $this->set(compact('users'));
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
        $user = $this->Users->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('user'));
    }

    /**
     * Add method
     *
     * @return Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function signup()
    {

        $this->Authorization->skipAuthorization();
        $user = $this->Users->newEmptyEntity();

        if ($this->request->is('post')) {
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
                            $user = $this->Users->patchEntity($user, $this->request->getData());
                            if ($this->Users->save($user)) {
                                $this->Flash->success(__('The user has been saved.'));

                                return $this->redirect([
                                    'action' => 'login'
                                ]);
                            }
                            $this->Flash->error(__('The user could not be saved. Please, try again.'));
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

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        // Configure the login action to not require authentication, preventing
        // the infinite redirect loop issue
        $this->Authentication->addUnauthenticatedActions(['login', 'add']);
    }

    public function login()
    {
        $this->Authorization->skipAuthorization();
        $this->request->allowMethod(['get', 'post']);
        $result = $this->Authentication->getResult();
        // regardless of POST or GET, redirect if user is logged in
        if ($result->isValid()) {

            if ($this->Authentication->getIdentity()->role === 'admin') {
                return $this->redirect([
                    'prefix' => 'Admin',
                    'controller' => 'Dashboard',
                    'action' => 'index'
                ]);
            }

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
        $this->Authorization->skipAuthorization();
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
}
