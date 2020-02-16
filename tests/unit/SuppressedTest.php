<?php

namespace Vantoozz\PHPCDM\UnitTests;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;
use Vantoozz\PHPCDM\DensityMeter;

/**
 * Class SuppressedTest
 * @package Vantoozz\PHPCDM\UnitTests
 */
final class SuppressedTest extends TestCase
{

    /**
     * @test
     */
    public function it_suppresses_the_meter()
    {
        $densityMeter = new DensityMeter(120);
        $density = $densityMeter->calculate(__DIR__ . '/../fixtures/suppressed/SomethingSuppressed.php');
        $this->assertSame(0.0, $density);
    }
}
