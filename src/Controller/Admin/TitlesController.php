<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use App\Model\Entity\Title;
use App\Model\Table\TitlesTable;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Datasource\ResultSetInterface;
use Cake\Http\Response;

/**
 * Titles Controller
 *
 * @property TitlesTable $Titles
 * @method Title[]|ResultSetInterface paginate($object = null, array $settings = [])
 */
class TitlesController extends AppController
{
    /**
     * Index method
     *
     * @return Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => [],
        ];

        $titles = $this->paginate($this->Titles);

        $query = $this->Titles->find();

        /**
         * Fetch amount of employees per title
         */
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
     * Title must be empty of any employee to be deleted
     * @param string|null $id Title id.
     * @return Response|null|void Redirects to index.
     * @throws RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        /**
         * Make sure the title has no employee affected to it
         */
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
            // If there isn't any employee affected to the title
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
