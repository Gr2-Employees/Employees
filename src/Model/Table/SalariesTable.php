<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Salaries Model
 *
 * @method \App\Model\Entity\Salary newEmptyEntity()
 * @method \App\Model\Entity\Salary newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Salary[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Salary get($primaryKey, $options = [])
 * @method \App\Model\Entity\Salary findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Salary patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Salary[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Salary|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Salary saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Salary[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Salary[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Salary[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Salary[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
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
