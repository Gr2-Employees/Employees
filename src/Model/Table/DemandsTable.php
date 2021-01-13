<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\Demand;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\ResultSetInterface;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Demands Model
 *
 * @method Demand newEmptyEntity()
 * @method Demand newEntity(array $data, array $options = [])
 * @method Demand[] newEntities(array $data, array $options = [])
 * @method Demand get($primaryKey, $options = [])
 * @method Demand findOrCreate($search, ?callable $callback = null, $options = [])
 * @method Demand patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method Demand[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method Demand|false save(EntityInterface $entity, $options = [])
 * @method Demand saveOrFail(EntityInterface $entity, $options = [])
 * @method Demand[]|ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method Demand[]|ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method Demand[]|ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method Demand[]|ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class DemandsTable extends Table
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

        $this->setTable('demands');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
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
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->integer('emp_no')
            ->requirePresence('emp_no', 'create')
            ->notEmptyString('emp_no');

        $validator
            ->scalar('type')
            ->requirePresence('type', 'create')
            ->notEmptyString('type')
            ->add('type', 'validValue',[
                'rule' => ['inlist',['raise','Department_change']],
                'message' => 'This value must be either raise or Department change.',
            ]);

        $validator
            ->scalar('about')
            ->maxLength('about', 255)
            ->requirePresence('about', 'create')
            ->notEmptyString('about');

        $validator
            ->scalar('status')
            ->notEmptyString('status');

        return $validator;
    }
}
