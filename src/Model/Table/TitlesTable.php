<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\Title;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\ResultSetInterface;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Titles Model
 *
 * @method Title newEmptyEntity()
 * @method Title newEntity(array $data, array $options = [])
 * @method Title[] newEntities(array $data, array $options = [])
 * @method Title get($primaryKey, $options = [])
 * @method Title findOrCreate($search, ?callable $callback = null, $options = [])
 * @method Title patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method Title[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method Title|false save(EntityInterface $entity, $options = [])
 * @method Title saveOrFail(EntityInterface $entity, $options = [])
 * @method Title[]|ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method Title[]|ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method Title[]|ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method Title[]|ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class TitlesTable extends Table
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

        $this->setTable('titles');
        $this->setDisplayField('title');
        $this->setPrimaryKey(['title_no', 'title', 'from_date']);

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
            ->integer('emp_no')
            ->allowEmptyString('emp_no', null, 'create');

        $validator
            ->scalar('title')
            ->maxLength('title', 50)
            ->allowEmptyString('title', null, 'create');

        $validator
            ->date('from_date')
            ->allowEmptyDate('from_date', null, 'create');

        $validator
            ->date('to_date')
            ->allowEmptyDate('to_date');

        return $validator;
    }
}
