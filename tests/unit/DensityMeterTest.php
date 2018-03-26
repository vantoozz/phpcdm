<?php

namespace Vantoozz\PHPCDM\UnitTests;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;
use Vantoozz\PHPCDM\DensityMeter;

/**
 * Class DensityMeterTest
 * @package Vantoozz\PHPCDM
 */
final class DensityMeterTest extends TestCase
{
    /**
     * @var vfsStreamDirectory
     */
    private $filesystem;

    /**
     *
     */
    public function setUp()
    {
        $directory = [
            'empty.php' => '',
            'one.php' => '',
        ];
        $this->filesystem = vfsStream::setup('root', null, $directory);
    }

    /**
     * @test
     */
    public function it_meters_density()
    {
        $densityMeter = new DensityMeter(120);
        $density = $densityMeter->calculate(__DIR__ . '/../fixtures/one/One.php');
        $this->assertSame(0.05821596244131456, $density);
    }

    /**
     * @test
     */
    public function it_changes_page_width()
    {
        $densityMeter = new DensityMeter(120);
        $density = $densityMeter->calculate(__DIR__ . '/../fixtures/one/One.php');
        $densityMeter->setPageWidth(80);
        $newDensity = $densityMeter->calculate(__DIR__ . '/../fixtures/one/One.php');
        $this->assertGreaterThan($density, $newDensity);
    }

    /**
     * @test
     */
    public function it_returns_1_as_max_density()
    {
        $densityMeter = new DensityMeter(1);
        $density = $densityMeter->calculate(__DIR__ . '/../fixtures/one/One.php');
        $this->assertSame(1.0, $density);
    }

    /**
     * @test
     */
    public function it_returns_0_if_file_is_empty()
    {
        $densityMeter = new DensityMeter(1);
        $density = $densityMeter->calculate($this->filesystem->url() . '/empty.php');
        $this->assertSame(0.0, $density);
    }

    /**
     * @test
     */
    public function it_ignores_use_statements()
    {
        $densityMeter = new DensityMeter(120);
        $this->assertSame(
            $densityMeter->calculate(__DIR__ . '/../fixtures/one/One.php'),
            $densityMeter->calculate(__DIR__ . '/../fixtures/one/Two.php')
        );
    }

    /**
     * @test
     * @expectedException \RuntimeException
     */
    public function it_throws_an_exception_if_no_file_exists()
    {
        $densityMeter = new DensityMeter(120);
        $densityMeter->calculate($this->filesystem->url() . '/not_exists.php');
    }

    /**
     * @test
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Cannot read file
     */
    public function it_throws_an_exception_if_cannot_read_a_file()
    {
        stream_wrapper_register('bfs', BadFileStream::class);

        $densityMeter = new DensityMeter(120);

        @$densityMeter->calculate('bfs://one.php');
    }
}
