<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SalariesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SalariesTable Test Case
 */
class SalariesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\SalariesTable
     */
    protected $Salaries;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Salaries',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Salaries') ? [] : ['className' => SalariesTable::class];
        $this->Salaries = $this->getTableLocator()->get('Salaries', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Salaries);

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
