<?php
declare(strict_types=1);

namespace App\Controller;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
/**
 * Vacancies Controller
 *
 * @property \App\Model\Table\VacanciesTable $Vacancies
 * @method \App\Model\Entity\Vacancy[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class VacanciesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $vacancies = $this->paginate($this->Vacancies);

        $this->set(compact('vacancies'));
    }

    public function showOffers()
    {
        // TODO: code
        // Get qString data

        $dept_no = $this->request->getQuery('dept');

        $vacancies = $this->getTableLocator()->get('Vacancies')
            ->find()
            ->select([
                'amount' => 'vacancies.quantity',
                'name' => 'de.dept_name',
                'title' => 'ti.title',
                'title_no' => 'ti.title_no'
            ])
            ->join([
                'ti' => [
                    'table' => 'titles',
                    'conditions' => 'ti.title_no = vacancies.title_no'
                ],
                'de' => [
                    'table' => 'departments',
                    'conditions' => 'de.dept_no = vacancies.dept_no'
                ]
            ])
            ->where([
                'vacancies.dept_no' => $dept_no
            ])
            ->group([
                'vacancies.title_no'
            ])
            ->all();

        if (!is_null($vacancies->first())) {
            $vacancyName = $vacancies->first()->name;

            $this->set(compact('vacancies'));
            $this->set(compact('vacancyName'));
        } else {
            $this->Flash->set(__('No vacant position in this department.'), [
                'element' => 'error'
            ]);
        }
    }

    public function applyOffer()
    {
        $title_no = $this->request->getQuery('title');

        $isFormSent = $this->request->is('post');

        if ($this->request->is('post')) {
            // Send mail to manager
            // temp mail pavajak211@pxjtw.com
            $temp = 'hepomi3066@pxjtw.com';
            // Get file sent by user

            $data = $this->request->getData();

            // Retrieve user info
            $from = $data['email'];
            $name = $data['surname'] . ' ' . $data['lastname'];

            // Get manager email
            $mail = new PHPMailer(true);
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'nathandeltour2@gmail.com';
            $mail->Password = 'hjalmmwupbkasarw';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->setFrom('nathandeltour2@gmail.com', 'Me');
            $mail->addAddress('hepomi3066@pxjtw.com', 'Joe Reagan');
            $mail->isHTML(true);
            $mail->Subject = 'Subject 123';
            $mail->Body = 'Body 123';
            $mail->send();

        }

        $this->set(compact('isFormSent'));
    }

    /**
     * View method
     *
     * @param string|null $id Vacancy id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $vacancy = $this->Vacancies->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('vacancy'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $vacancy = $this->Vacancies->newEmptyEntity();
        if ($this->request->is('post')) {
            $vacancy = $this->Vacancies->patchEntity($vacancy, $this->request->getData());
            if ($this->Vacancies->save($vacancy)) {
                $this->Flash->success(__('The vacancy has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The vacancy could not be saved. Please, try again.'));
        }
        $this->set(compact('vacancy'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Vacancy id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $vacancy = $this->Vacancies->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $vacancy = $this->Vacancies->patchEntity($vacancy, $this->request->getData());
            if ($this->Vacancies->save($vacancy)) {
                $this->Flash->success(__('The vacancy has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The vacancy could not be saved. Please, try again.'));
        }
        $this->set(compact('vacancy'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Vacancy id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $vacancy = $this->Vacancies->get($id);
        if ($this->Vacancies->delete($vacancy)) {
            $this->Flash->success(__('The vacancy has been deleted.'));
        } else {
            $this->Flash->error(__('The vacancy could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
