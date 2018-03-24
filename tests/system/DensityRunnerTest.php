<?php declare(strict_types=1);

namespace Vantoozz\Density\SystemTests;

use PHPUnit\Framework\TestCase;

/**
 * Class DensityCommandTest
 * @package Vantoozz\Density\IntegrationTests
 */
class DensityRunnerTest extends TestCase
{

    /**
     * @var string
     */
    private $command;

    /**
     *
     */
    public function setUp()
    {
        $this->command = __DIR__ . '/../../bin/density ';
    }

    /**
     * @test
     */
    public function in_run_analyze_command_by_default()
    {
        $output = [];
        exec($this->command . ' 2>&1', $output);
        $output = trim(implode(' ', $output));
        $this->assertRegexp('/Not enough arguments \(missing: "directories"\)/', $output);
    }

    /**
     * @test
     */
    public function in_does_not_run_explicit_analyze_command()
    {
        $output = [];
        exec($this->command . ' analyze 2>&1', $output);
        $output = trim(implode(' ', $output));
        $this->assertRegexp('/The "analyze" directory does not exist/', $output);
    }

}