<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use App\Model\Entity\Department;
use App\Model\Table\DepartmentsTable;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Datasource\ResultSetInterface;
use Cake\Http\Response;

/**
 * Departments Controller
 *
 * @property DepartmentsTable $Departments
 * @method Department[]|ResultSetInterface paginate($object = null, array $settings = [])
 */
class DashboardController extends AppController
{
    /**
     * Index method
     *
     * @return Response|null|void Renders view
     */
    public function index()
    {
        if($this->Authentication->getIdentity()['role']==='admin'){
            $this->Authorization->skipAuthorization();
        }

        $welcomeMessage = '<h1>Welcome in the back-office!</h1>';

        $this->set(compact('welcomeMessage'));
    }
}
