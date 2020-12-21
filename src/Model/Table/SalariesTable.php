<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\Salary;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\ResultSetInterface;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Salaries Model
 *
 * @method Salary newEmptyEntity()
 * @method Salary newEntity(array $data, array $options = [])
 * @method Salary[] newEntities(array $data, array $options = [])
 * @method Salary get($primaryKey, $options = [])
 * @method Salary findOrCreate($search, ?callable $callback = null, $options = [])
 * @method Salary patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method Salary[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method Salary|false save(EntityInterface $entity, $options = [])
 * @method Salary saveOrFail(EntityInterface $entity, $options = [])
 * @method Salary[]|ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method Salary[]|ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method Salary[]|ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method Salary[]|ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class SalariesTable extends Table
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

        $this->setTable('salaries');
        $this->setDisplayField('emp_no');
        $this->setPrimaryKey(['emp_no', 'from_date']);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('emp_no')
            ->allowEmptyString('emp_no', null, 'create');

        $validator
            ->integer('salary')
            ->requirePresence('salary', 'create')
            ->notEmptyString('salary');

        $validator
            ->date('from_date')
            ->allowEmptyDate('from_date', null, 'create');

        $validator
            ->date('to_date')
            ->requirePresence('to_date', 'create')
            ->notEmptyDate('to_date');

        return $validator;
    }
}
