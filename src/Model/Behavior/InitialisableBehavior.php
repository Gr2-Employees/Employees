<?php
declare(strict_types=1);

namespace App\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\ORM\Table;
use Cake\Event\Event;
use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Entity;
use Cake\ORM\Query;

/**
 * Initialisable behavior
 */
class InitialisableBehavior extends Behavior
{
    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'firstname' => 'first_name',
        'lastname' => 'last_name',
        'target' => 'initials',
        'delimiter' => '.',
    ];
    
    public function initials(Entity $entity)
    {
        $config = $this->getConfig();
        $fn = $entity->get($config['firstname']);
        $ln = $entity->get($config['lastname']);
        $delimiter = $config['delimiter'];
        
        $entity->set($config['target'], "{$fn[0]}$delimiter {$ln[0]}$delimiter");   //C. R.
    }

    public function beforeSave(Event $event, EntityInterface $entity, ArrayObject $options)
    {
        $this->initials($entity);
    }
}
