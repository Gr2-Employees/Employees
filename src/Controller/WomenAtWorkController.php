<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\Event\EventInterface;

require_once(ROOT.DS.'vendor'.DS.'GoogleCharts'.DS.'vendor'.DS.'GoogleCharts.php');
/**
 * Partners Controller
 *
 * @property \App\Model\Table\PartnersTable $Partners
 * @method \App\Model\Entity\Partner[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class WomenAtWorkController extends AppController
{
    public function index()
    {
        echo "<h1>Page WomenAtWork</h1>";
    }

    public function beforeRender(EventInterface $event)
    {
        parent::beforeRender($event);
    }
}
