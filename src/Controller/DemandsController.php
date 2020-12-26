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
        $demands = $this->paginate($this->Demands);

        $this->set(compact('demands'));
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
        $demand = $this->Demands->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('demand'));
    }

    public function approve($id = null)
    {
        $this->disableAutoRender();
        if ($id == null) {
            return $this->redirect(['action' => 'index']);
        }
        //Récuperation du role
        $role = $this->Authentication->getIdentity()->role;
        $query = $this->getTableLocator()->get('Demands')
            ->find()
            ->select([
                'approved_by'
            ])
            ->where([
                'id' => $id
            ]);

        $approvedRole = $query->first()->approved_by;

        switch ($approvedRole) {
            //Insérer le role dans le champ approved_by
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
                }
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
                }
            default;
        }
    }

    public function decline($id = null)
    {

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
        } else {
            $this->Flash->error(__('There was a problem declining the demand, please try again.'));

            return $this->redirect([
                'action' => 'index'
            ]);
        }
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
            if ($this->Demands->save($demand)) {
                $this->Flash->success(__('The demand has been saved.'));

                return $this->redirect(['action' => 'index']);
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
            if ($this->Authentication->getIdentity()->role !== 'member'
                && $this->Authentication->getIdentity()->role !== 'admin'
                && $this->Authentication->getIdentity()->role !== 'comptable'
                && $this->Authentication->getIdentity()->role !== 'manager')
            {
                return $this->redirect('/');

            }
        } else {
            $this->Flash->error('You must be connected');
            return $this->redirect('/');
        }
    }
}
