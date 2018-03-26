<?php

namespace Vantoozz\PHPCDM\SystemTests;

use PHPUnit\Framework\TestCase;

/**
 * Class DensityCommandTest
 * @package Vantoozz\PHPCDM\IntegrationTests
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
        $this->command = __DIR__ . '/../../bin/phpcdm ';
    }

    /**
     * @test
     */
    public function in_run_analyze_command_by_default()
    {
        $output = [];
        exec($this->command . ' 2>&1', $output);
        $output = trim(implode(' ', $output));
        $this->assertRegExp('/Not enough arguments \(missing: "directories"\)/', $output);
    }

    /**
     * @test
     */
    public function in_does_not_run_explicit_analyze_command()
    {
        $output = [];
        exec($this->command . ' analyze 2>&1', $output);
        $output = trim(implode(' ', $output));
        $this->assertRegExp('/The "analyze" directory does not exist/', $output);
    }

    /**
     * @test
     */
    public function in_returns_non_zero_exit_code()
    {
        $output = [];
        $code = 0;
        $directory = realpath(__DIR__ . '/../fixtures/one');
        exec($this->command . $directory . ' --threshold=0.001 --non-zero-exit-on-violation 2>&1', $output, $code);
        $output = trim(implode(' ', $output));
        $this->assertRegExp('/One.php has density of 0\.058/', $output);
        $this->assertSame(1, $code);
    }
}