<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Entity\Demand;
use App\Model\Table\DemandsTable;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Datasource\ResultSetInterface;
use Cake\Event\EventInterface;
use Cake\Http\Response;

/**
 * Demands Controller
 *
 * @property DemandsTable $Demands
 * @method Demand[]|ResultSetInterface paginate($object = null, array $settings = [])
 */
class DemandsController extends AppController
{
    /**
     * Index method
     *
     * @return Response|null|void Renders view
     */
    public function index()
    {
        if ($this->Authentication->getIdentity()->role === 'member') {
            $this->Flash->error('Vous ne pouvez pas accéder a cette page');
            return $this->redirect([
                'controller' => 'departments',
                'action' => 'index'
            ]);
        }

        $query = $this->Demands->findAllByType('Department change');

        $result = $query->all();
        $demandsDepartment = [];

        foreach ($result as $row) {
            $demandsDepartment[] = $row;
        }

        //Table demands de type raise + salaire actuel
        $queryS = $this->getTableLocator()->get('demands')
        ->find()
        ->select([
            'demands.id',
            'emp_no',
            'type',
            'about',
            'status',
            'amount',
            's.salary'
        ])
        ->join([
            's' => [
                'table' => 'salaries',
                'conditions' => 's.emp_no = demands.emp_no'
            ]
        ])
        ->where([
            's.to_date' => '9999-01-01',
            'demands.type' => 'raise'
        ]);

        $result = $queryS->all();

        $demandsRaise = [];

        foreach ($result as $row) {
            $demandsRaise[] = $row;
        }
        $this->set(compact('demandsRaise'));
        $this->set(compact('demandsDepartment'));
    }

    /**
     * View method
     *
     * @param string|null $id Demand id.
     * @return Response|null|void Renders view
     * @throws RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        if ($this->Authentication->getIdentity()->role === 'member') {
            $this->Flash->error('Vous ne pouvez pas accéder a cette page');

            return $this->redirect([
                'controller' => 'departments',
                'action' => 'index'
            ]);
        }

        $demand = $this->Demands->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('demand'));
    }

    /**
     * Approve demand method
     * @param null $id
     * @return Response|null
     */
    public function approve($id = null)
    {
        if ($this->Authentication->getIdentity()->role === 'member') {
            $this->Flash->error('Vous ne pouvez pas accéder a cette page');

            return $this->redirect([
                'controller' => 'departments',
                'action' => 'index'
            ]);
        }

        // Removes the need to have a dedicated template
        $this->disableAutoRender();

        if ($id === null) {
            return $this->redirect([
                'action' => 'index'
            ]);
        }

        if (!empty($this->request->getQuery())) {
            if (!empty($this->request->getQuery()['type']) && $this->request->getQuery()['type'] === 'raise') {
                $query = $this->getTableLocator()->get('demands')->find()
                ->select([
                    'amount',
                    'emp_no'
                ])
                ->where([
                    'id' => $id
                ]);
                $result = $query->first();

                $amount = $result->amount;
                $emp_no = $result->emp_no;

                //Extract employee's salary
                $querySalary = $this->getTableLocator()->get('salaries')->find()
                ->select([
                    'salary'
                ])
                ->where([
                    'emp_no' => $emp_no,
                    'to_date' => '9999-01-01'
                ]);

                $salary = $querySalary->first()->salary;

                // Calcul nouveau salaire
                $newSalary = floor($salary + (($salary / 100) * $amount));

                // Update old salary's data
                $update = $this->getTableLocator()->get('Salaries')->query();
                $update->update()
                ->set([
                    'to_date' => $update->func()->now()
                ])
                ->where([
                    'emp_no' => $emp_no,
                    'to_date' => '9999-01-01'
                ]);

                if ($update->execute()) {
                    $insert = $this->getTableLocator()->get('Salaries')->query();
                    $insert->insert([
                        'emp_no',
                        'salary',
                        'from_date',
                        'to_date'
                    ])
                    ->values([
                        'emp_no' => $emp_no,
                        'salary' => $newSalary,
                        'from_date' => $insert->func()->now(),
                        'to_date' => '9999-01-01',
                    ]);

                    if ($insert->execute()) {
                        $updateType = $this->getTableLocator()->get('Demands')
                            ->query()
                            ->update()
                            ->set([
                                'status' => 'validated'
                            ])
                            ->where([
                                'id' => $id
                            ]);

                        if ($updateType->execute()) {
                            $this->Flash->success('The salary has been updated');
                        } else {
                            $this->Flash->error('The salary has been a problem');
                        }
                    } else {
                        $this->Flash->error('There was a problem while inserting new salary');
                    }
                } else {
                    $this->Flash->error('There was a problem while updating the date');
                }
            } else {
                $this->Flash->error('There was a problem.');
            }
            return $this->redirect(['action' => 'index']);
        }

        // Authed user's role
        $role = $this->Authentication->getIdentity()->role;

        //Récuperation du role ayant approuvé la demande
        $query = $this->getTableLocator()->get('Demands')
        ->find()
        ->select([
            'approved_by'
        ])
        ->where([
            'id' => $id,
        ]);

        $approvedRole = $query->first()->approved_by;

        switch ($approvedRole) {
            // Insérer le role dans le champ approved_by
            case 'none' :
                $query = $this->getTableLocator()->get('Demands')->query();
                $query->update()
                ->set([
                    'approved_by' => $role
                ])
                ->where([
                    'id' => $id
                ])
                ->execute();

                $this->redirect([
                    'action' => 'index'
                ]);
            break;

            //Comparer le role (different)
            case 'manager' :
                if ($role !== $approvedRole) {
                    // Set demand's status to validated if both needed role are present
                    $query = $this->getTableLocator()->get('Demands')->query();
                    $query->update()
                    ->set([
                        'approved_by' => 'both',
                        'status' => 'validated'
                    ])
                    ->where([
                        'id' => $id
                    ])
                    ->execute();
                } else {
                    $this->Flash->error('This demand is already approved by a manager');
                }

                $this->redirect([
                    'action' => 'index'
                ]);
            break;

            //Comparer le role (different)
            case 'comptable' :
                if ($role !== $approvedRole) {
                    $query = $this->getTableLocator()->get('Demands')->query();
                    $query->update()
                    ->set([
                        'approved_by' => 'both',
                        'status' => 'validated'
                    ])
                    ->where([
                        'id' => $id
                    ])
                    ->execute();

                    $this->redirect([
                        'action' => 'index'
                    ]);
                } else {
                    $this->Flash->error('This demand is already approved by an accountant');
                }
                $this->redirect([
                    'action' => 'index'
                ]);
            default;
        }

    }

    /**
     * Decline demand method
     * @param int $id Demand id
     * @return Response|null
     */
    public function decline($id = null)
    {
        if ($this->Authentication->getIdentity()->role === 'member') {
            $this->Flash->error('Vous ne pouvez pas accéder a cette page');

            return $this->redirect([
                'controller' => 'departments',
                'action' => 'index'
            ]);
        }

        // Removes the need to have a dedicated template
        $this->disableAutoRender();

        if ($id === null) {
            return $this->redirect([
                'action' => 'index'
            ]);
        }

        $demand = $this->Demands->get($id);
        $demand->status = 'declined';

        if ($this->Demands->save($demand)) {
            $this->Flash->success(__('The demand has been declined'));

            return $this->redirect([
                'action' => 'index'
            ]);
        }

        $this->Flash->error(__('There was a problem declining the demand, please try again.'));

        return $this->redirect([
            'action' => 'index'
        ]);
    }

    /**
     * Add method
     *
     * @return Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $demand = $this->Demands->newEmptyEntity();

        if ($this->request->is('post')) {
            $demand = $this->Demands->patchEntity($demand, $this->request->getData());
            $demand->emp_no = $this->Authentication->getIdentity()->emp_no;
            $demand->type = $this->request->getData('type');

            if ($demand->type === 'Department change') {
                $demand->amount = null;
            } else {
                $demand->amount = $this->request->getData('amount');
            }

            if ($this->Demands->save($demand)) {
                $this->Flash->success(__('The demand has been sent.'));

                return $this->redirect([
                    'controller' => 'departments',
                    'action' => 'index'
                ]);
            }
            $this->Flash->error(__('The demand could not be saved. Please, try again.'));
        }
        $this->set(compact('demand'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Demand id.
     * @return Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        if ($this->Authentication->getIdentity()->role === 'member') {
            $this->Flash->error('Vous ne pouvez pas accéder a cette page');
            return $this->redirect([
                'controller' => 'departments',
                'action' => 'index'
            ]);
        }

        $demand = $this->Demands->get($id, [
            'contain' => [],
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $demand = $this->Demands->patchEntity($demand, $this->request->getData());

            if ($this->Demands->save($demand)) {
                $this->Flash->success(__('The demand has been saved.'));

                return $this->redirect(['action' => 'index']);
            }

            $this->Flash->error(__('The demand could not be saved. Please, try again.'));
        }
        $this->set(compact('demand'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Demand id.
     * @return Response|null|void Redirects to index.
     * @throws RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        if ($this->Authentication->getIdentity()->role !== 'admin') {
            $this->Flash->error('Vous ne pouvez pas accéder a cette page');

            return $this->redirect([
                'controller' => 'departments',
                'action' => 'index'
            ]);
        }

        $this->request->allowMethod(['post', 'delete']);
        $demand = $this->Demands->get($id);

        if ($this->Demands->delete($demand)) {
            $this->Flash->success(__('The demand has been deleted.'));
        } else {
            $this->Flash->error(__('The demand could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function beforeRender(EventInterface $event)
    {
        parent::beforeRender($event);

        if ($this->Authentication->getResult()->isValid()) {
            $allowedRoles = ['member', 'admin', 'comptable', 'manager'];
            if (!in_array($this->Authentication->getIdentity()->role, $allowedRoles, true)) {
                return $this->redirect('/');
            }
        } else {
            $this->Flash->error('You must be connected');
            return $this->redirect('/');
        }
    }
}
