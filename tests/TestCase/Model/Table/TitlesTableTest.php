<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TitlesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TitlesTable Test Case
 */
class TitlesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\TitlesTable
     */
    protected $Titles;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Titles',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Titles') ? [] : ['className' => TitlesTable::class];
        $this->Titles = $this->getTableLocator()->get('Titles', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Titles);

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
