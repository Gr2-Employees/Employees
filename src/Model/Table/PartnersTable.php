<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\Partner;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\ResultSetInterface;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Partners Model
 *
 * @method Partner newEmptyEntity()
 * @method Partner newEntity(array $data, array $options = [])
 * @method Partner[] newEntities(array $data, array $options = [])
 * @method Partner get($primaryKey, $options = [])
 * @method Partner findOrCreate($search, ?callable $callback = null, $options = [])
 * @method Partner patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method Partner[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method Partner|false save(EntityInterface $entity, $options = [])
 * @method Partner saveOrFail(EntityInterface $entity, $options = [])
 * @method Partner[]|ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method Partner[]|ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method Partner[]|ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method Partner[]|ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class PartnersTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('partners');
        $this->setDisplayField('title');
        $this->setPrimaryKey('title');
    }

    /**
     * Default validation rules.
     *
     * @param Validator $validator Validator instance.
     * @return Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('title')
            ->maxLength('title', 255)
            ->allowEmptyString('title', null, 'create');

        $validator
            ->scalar('url')
            ->maxLength('url', 255)
            ->requirePresence('url', 'create')
            ->notEmptyString('url');

        $validator
            ->scalar('logo')
            ->maxLength('logo', 255)
            ->requirePresence('logo', 'create')
            ->notEmptyString('logo');

        return $validator;
    }
}
