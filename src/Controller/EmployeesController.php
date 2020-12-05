<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\View\CellTrait;

/**
 * Employees Controller
 *
 * @property \App\Model\Table\EmployeesTable $Employees
 * @method \App\Model\Entity\Employee[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class EmployeesController extends AppController
{
    use CellTrait;
    
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        //Récupérer les données de la base de données
        $employees = $this->Employees;
       
        //Préparer, modifier ces données
        $employees = $this->paginate($employees);
        
        $cellMenWomenRatio = $this->cell('Inbox');
        
        //Envoyer vers la vue
        $this->set('employees',$employees);
        $this->set('cellMenWomenRatio',$cellMenWomenRatio);
    }

    /**
     * View method
     *
     * @param string|null $id Employee id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $employee = $this->Employees->get($id, [
            'contain' => ['salaries','titles'],
        ]);
        
        $titles = $employee->titles;
        $today = new \DateTime();
        foreach($titles as $title) {
            $date = new \DateTime($title->to_date->format('Y-m-d'));
            
            if($date > $today) {
                $employee->fonction = $title->title;
                break;
            }
        }

        $this->set(compact('employee'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        //Récupérer => Créer
        $employee = $this->Employees->newEmptyEntity();
        
        $employee->password = hash($pass);
        //Traitement
        //Rien faire en GET
        //Persister en POST
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
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
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
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
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
    
    public function getAllByGender(string $gender = 'f') {
        //Récupérer les données
        $employees = $this->Employees->findByGender($gender)->limit(10);
        
        //Transformer
        $employees = $this->paginate($employees);
        
        //Envoyer à la vue
        $this->set('employees',$employees);
        $this->render('index'); //Définit un temlate spécifique
    }
}
