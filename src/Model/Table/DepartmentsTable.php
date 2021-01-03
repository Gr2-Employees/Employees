<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\Department;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\ResultSetInterface;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Departments Model
 *
 * @method Department newEmptyEntity()
 * @method Department newEntity(array $data, array $options = [])
 * @method Department[] newEntities(array $data, array $options = [])
 * @method Department get($primaryKey, $options = [])
 * @method Department findOrCreate($search, ?callable $callback = null, $options = [])
 * @method Department patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method Department[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method Department|false save(EntityInterface $entity, $options = [])
 * @method Department saveOrFail(EntityInterface $entity, $options = [])
 * @method Department[]|ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method Department[]|ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method Department[]|ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method Department[]|ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class DepartmentsTable extends Table
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

        $this->setTable('departments');
        $this->setDisplayField('dept_no');
        $this->setPrimaryKey('dept_no');

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
            ->scalar('dept_name')
            ->maxLength('dept_name', 40)
            ->requirePresence('dept_name', 'create')
            ->notEmptyString('dept_name')
            ->add('dept_name', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->notEmptyString('description');

        $validator
            ->notEmptyString('address');

        /*$validator
            ->notEmptyString('rules');*/

        $validator
            ->notEmptyFile('picture')
            ->uploadedFile('picture', [
                'types' => ['image/png', 'image/jpg', 'image/jpeg'], // only PNG, JPEG and JPG image files
                'minSize' => 1024, // Min 1 KB
                'maxSize' => 1024 * 1024 // Max 1 MB
            ])
            ->add('picture', 'minImageSize', [
                'rule' => ['imageSize', [
                    // Min 10x10 pixel
                    'width' => [10],
                    'height' => [10],
                ]]
            ])
            ->add('picture', 'maxImageSize', [
                'rule' => ['imageSize', [
                    // Max 600x600 pixel
                    'width' => [600],
                    'height' => [600],
                ]]
            ])
            ->add('picture', 'extension', [
                'rule' => ['extension', [
                    'png', 'jpeg', 'jpg' // .png file extension only
                ]]
            ]);

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param RulesChecker $rules The rules object to be modified.
     * @return RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['dept_name']), ['errorField' => 'dept_name']);

        return $rules;
    }
}
