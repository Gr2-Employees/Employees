<?php
declare(strict_types=1);

namespace App\Controller;

use Laminas\Diactoros\UploadedFile;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Psr\Http\Message\UploadedFileFactoryInterface;

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
        $dept_no = $this->request->getQuery('dept_no');

        $vacancies = $this->getTableLocator()->get('Vacancies')
            ->find()
            ->select([
                'amount' => 'vacancies.quantity',
                'name' => 'de.dept_name',
                'title' => 'ti.title',
                'title_no' => 'ti.title_no',
                'dept_no' => 'de.dept_no'
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
            $nbVacancies = $vacancies->count();
            $vacancyName = $vacancies->first()->name;

            $this
                ->set(compact('vacancies'))
                ->set(compact('nbVacancies'))
                ->set(compact('vacancyName'));
        } else {
            $this->Flash->set(__('No vacant position in this department.'), [
                'element' => 'error'
            ]);
        }
    }

    public function applyOffer() {
        // Init form value
        $showForm = true;

        // Populate hidden fields
        $title_no = $this->request->getQuery('title_no');
        $dept_no = $this->request->getQuery('dept_no');

        if ($this->request->is('post')) {
            $formData = $this->request->getData();
            if (!empty($formData)
                && !empty($formData['dept_no'])
                && !empty($formData['title_no'])
                && !empty($formData['surname'])
                && !empty($formData['lastname'])
                && !empty($formData['email'])
                && !empty($formData['birthdate'])
                && !empty($formData['motivations'])
                && !empty($_FILES['file']['size']))
            {
                // Check if file is .pdf or .word
                if ($_FILES['file']['type'] === 'application/pdf' || $_FILES['file']['type'] === 'application/msword') {

                    // Copy uploaded file to local folder
                    $file = $formData['file'];
                    $uploadPath = '../webroot/files/uploads/';
                    $uploadedFile = $uploadPath . $file->getClientFileName();
                    $file->moveTo($uploadedFile);

                    // Get manager's mail + name
                    $dept_no = $formData['dept_no'];
                    $query = $this->getTableLocator()->get('dept_manager')
                        ->find()
                        ->select([
                            'email',
                            'em.first_name',
                            'em.last_name'
                        ])
                        ->join([
                            'em' => [
                                'table' => 'employees',
                                'conditions' => 'em.emp_no = dept_manager.emp_no'
                            ]
                        ])
                        ->where([
                            'dept_no' => $dept_no,
                            'to_date' => '9999-01-01'
                        ])
                        ->first();

                    // Manager info
                    $managerMail = $query->email;
                    $managerName = $query->em['first_name'] . ' ' . $query->em['last_name'];

                    // User info
                    $from = $formData['email'];
                    $eName = $formData['surname'] . ' ' . $formData['lastname'];

                    // Get position's name
                    $query = $this->getTableLocator()->get('titles')
                        ->find()
                        ->select([
                            'title'
                        ])
                        ->where([
                            'title_no' => $formData['title_no']

                        ])
                        ->first();

                    $titleName = $query->title;

                    // Setting up mail body
                    $mailBody = __('I am '
                        . $eName . ' , born the ' . $formData['birthdate']
                        . '. <br/> Here are my motivations :<br/>'
                        . $formData['motivations']
                    );

                    // Send email to manager
                    $mail = new PHPMailer(true);
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'nathandeltour2@gmail.com';
                    $mail->Password = 'hjalmmwupbkasarw';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;
                    $mail->setFrom($from, $eName);
                    $mail->addAddress($managerMail, $managerName);
                    $mail->isHTML(true);
                    $mail->Subject = __('Applying for ' . $titleName . ' position');
                    $mail->Body = $mailBody;
                    $mail->addAttachment($uploadedFile, $eName . ' CV');
                    $mail->send();

                    if ($mail) {
                        $this->Flash->set(__('Your mail has been sent to ' . $managerName . ' (manager of department ' . $dept_no . '). Thank you !'), [
                            'element' => 'success'
                        ]);

                        // Redirect to dept view if mail has been successfully sent.
                        $this->redirect([
                            'controller' => 'Departments',
                            'action' => 'view',
                            $dept_no
                        ]);
                    } else {
                        $this->Flash->set(__('An error occurred when sending your mail, please try again.'), [
                            'element' => 'error',
                        ]);

                        // Show form again
                        $showForm = true;
                    }
                } else {
                    // If file isn't .pdf or .word
                    $this->Flash->set(__('Please upload a PDF or Word file for your CV.'), [
                        'element' => 'error',
                    ]);
                    $showForm = true;
                }
            } else {
                $this->Flash->set(__('Please make sure to fill in all the informations.'), [
                    'element' => 'error',
                ]);

                $showForm = true;
            }
        } else {
            // Show form if user hasn't clicked Submit button
            $showForm = true;
        }

        $this
            ->set(compact('showForm'))
            ->set(compact('dept_no'))
            ->set(compact('title_no'));
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
