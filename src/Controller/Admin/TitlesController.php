<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * Titles Controller
 *
 * @property \App\Model\Table\TitlesTable $Titles
 * @method \App\Model\Entity\Title[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class TitlesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => [],
        ];

        $titles = $this->paginate($this->Titles);

        $query = $this->Titles->find();
        $query->select([
            'nbEmpl' => $query->func()->count('emti.emp_no'),
            'Titles.title'
        ])
        ->join([
            'emti' => [
                'table' => 'employee_title',
                'type' => 'LEFT',
                'conditions' => 'emti.title_no = titles.title_no'
            ]
        ])
        ->group([
            'Titles.title_no'
        ]);


        foreach($titles as $title) {
            foreach($query->all() as $row) {
                if ($row->title === $title->title) {
                    $title->nbEmpl = $row->nbEmpl;
                }
            }
        }

        $this->set(compact('titles'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Title id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {

        $this->request->allowMethod(['post', 'delete']);

        $searchAmount = $this->getTableLocator()->get('Titles')->find();
        $searchAmount->select([
            'nbEmpl' => $searchAmount->func()->count('*')
            ])
            ->join([
                'emti' => [
                    'table' => 'employee_title',
                    'type' => 'LEFT',
                    'conditions' => 'emti.title_no = Titles.title_no'
                ]
            ])
            ->where([
                'emti.title_no' => $id
            ]);

        if (sizeof($searchAmount->all()) === 1) {
            if ($searchAmount->first()->nbEmpl === 0) {
                $delete = $this->getTableLocator()->get('Titles')->query();
                $delete->delete()
                    ->where([
                        'Titles.title_no' => $id
                    ]);

                if ($delete->execute()) {
                    $this->Flash->success(__('The title has been deleted.'));
                } else {
                    $this->Flash->error(__('The title could not be deleted. Please, try again.'));
                }
            } else {
                $this->Flash->error(__('The title mustn\'t have any employee affected to it. Please, try again.'));
            }
        }

        return $this->redirect(['action' => 'index']);
    }
}
