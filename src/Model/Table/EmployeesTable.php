<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\Employee;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\ResultSetInterface;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Behavior\InitialisableBehavior;

/**
 * Employees Model
 *
 * @method Employee newEmptyEntity()
 * @method Employee newEntity(array $data, array $options = [])
 * @method Employee[] newEntities(array $data, array $options = [])
 * @method Employee get($primaryKey, $options = [])
 * @method Employee findOrCreate($search, ?callable $callback = null, $options = [])
 * @method Employee patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method Employee[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method Employee|false save(EntityInterface $entity, $options = [])
 * @method Employee saveOrFail(EntityInterface $entity, $options = [])
 * @method Employee[]|ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method Employee[]|ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method Employee[]|ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method Employee[]|ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class EmployeesTable extends Table
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

        $this->addBehavior('Initialisable');    //Permet de générer les initiales

        $this->setTable('employees');
        $this->setDisplayField('emp_no');
        $this->setPrimaryKey('emp_no');

        $this->hasMany('salaries', [
            'foreignKey' => 'emp_no',
        ]);

        $this->hasMany('employee_title', [
            'foreignKey' => 'emp_no',
        ]);

        $this->belongsToMany('Departments',[
            'joinTable' => 'dept_emp',
            'targetForeignKey' => 'dept_no',
            'foreignKey' => 'emp_no',
            'bindingKey' => 'emp_no',
        ]);
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
            ->date('birth_date')
            ->requirePresence('birth_date', 'create')
            ->notEmptyDate('birth_date');

        $validator
            ->scalar('first_name')
            ->maxLength('first_name', 14)
            ->requirePresence('first_name', 'create')
            ->notEmptyString('first_name');

        $validator
            ->scalar('last_name')
            ->maxLength('last_name', 16)
            ->requirePresence('last_name', 'create')
            ->notEmptyString('last_name');

        $validator
            ->scalar('gender')
            ->requirePresence('gender', 'create')
            ->notEmptyString('gender')
            ->add('gender', 'validValue',[
                'rule' => ['inlist',['F','M']],
                'message' => 'This value must be either F or M',
            ]);

        $validator
            ->date('hire_date')
            ->requirePresence('hire_date', 'create')
            ->notEmptyDate('hire_date');

        return $validator;
    }

    function findSpecialSearch(Query $query, array $options) {
        $query->where([]);

        return $query;
    }

    //Récupérer tous les employés d'un département donné

    //Recupérer les département de plus de 100 employes qui ne travaillent plus dans ce département
}
