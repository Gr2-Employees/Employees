<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\VacanciesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\VacanciesTable Test Case
 */
class VacanciesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\VacanciesTable
     */
    protected $Vacancies;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Vacancies',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Vacancies') ? [] : ['className' => VacanciesTable::class];
        $this->Vacancies = $this->getTableLocator()->get('Vacancies', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Vacancies);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
