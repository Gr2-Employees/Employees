<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\Vacancy;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\ResultSetInterface;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Vacancies Model
 *
 * @method Vacancy newEmptyEntity()
 * @method Vacancy newEntity(array $data, array $options = [])
 * @method Vacancy[] newEntities(array $data, array $options = [])
 * @method Vacancy get($primaryKey, $options = [])
 * @method Vacancy findOrCreate($search, ?callable $callback = null, $options = [])
 * @method Vacancy patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method Vacancy[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method Vacancy|false save(EntityInterface $entity, $options = [])
 * @method Vacancy saveOrFail(EntityInterface $entity, $options = [])
 * @method Vacancy[]|ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method Vacancy[]|ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method Vacancy[]|ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method Vacancy[]|ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class VacanciesTable extends Table
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

        $this->setTable('vacancies');
        $this->setDisplayField('dept_no');
        $this->setPrimaryKey(['dept_no', 'title_no']);
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
            ->scalar('dept_no')
            ->maxLength('dept_no', 4)
            ->allowEmptyString('dept_no', null, 'create');

        $validator
            ->integer('title_no')
            ->allowEmptyString('title_no', null, 'create');

        $validator
            ->integer('quantity')
            ->requirePresence('quantity', 'create')
            ->notEmptyString('quantity');

        return $validator;
    }
}
