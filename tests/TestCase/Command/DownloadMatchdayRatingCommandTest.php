<?php
declare(strict_types=1);

namespace App\Test\TestCase\Command;

use Cake\TestSuite\ConsoleIntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Command\DownloadMatchdayRatingCommand Test Case
 *
 * @uses \App\Command\DownloadMatchdayRatingCommand
 */
class DownloadMatchdayRatingCommandTest extends TestCase
{
    use ConsoleIntegrationTestTrait;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->useCommandRunner();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildOptionParser method
     *
     * @return void
     */
    public function testBuildOptionParser(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test execute method
     *
     * @return void
     */
    public function testExecute(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test exec method
     *
     * @return void
     */
    public function testExec(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
