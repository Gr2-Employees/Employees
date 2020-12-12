<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * VacanciesFixture
 */
class VacanciesFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // phpcs:disable
    public $fields = [
        'dept_no' => ['type' => 'char', 'length' => 4, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_general_ci', 'comment' => '', 'precision' => null],
        'title_no' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'quantity' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'dept_no' => ['type' => 'index', 'columns' => ['dept_no', 'title_no'], 'length' => []],
            'title_no' => ['type' => 'index', 'columns' => ['title_no'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['dept_no', 'title_no'], 'length' => []],
            'vacancies_ibfk_2' => ['type' => 'foreign', 'columns' => ['title_no'], 'references' => ['titles', 'title_no'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'vacancies_ibfk_1' => ['type' => 'foreign', 'columns' => ['dept_no'], 'references' => ['departments', 'dept_no'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_general_ci'
        ],
    ];
    // phpcs:enable
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'dept_no' => '',
                'title_no' => 1,
                'quantity' => 1,
            ],
        ];
        parent::init();
    }
}
