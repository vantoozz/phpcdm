<?php


namespace Vantoozz\PHPCDM\UnitTests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Finder\Finder;
use Vantoozz\PHPCDM\DensityCommand;
use Vantoozz\PHPCDM\DensityMeter;
use Vantoozz\PHPCDM\DensityMeterInterface;

final class DensityCommandTest extends TestCase
{

    /**
     * @test
     */
    public function it_runs()
    {
        $command = new DensityCommand(new DensityMeter(80), $this->makeFinder([]));
        $this->assertSame(0, $command->run(new ArrayInput(['directories' => ['some_dir']]), new NullOutput()));
    }

    /**
     * @param array $files
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    private function makeFinder(array $files)
    {
        $finder = $this->getMockBuilder(Finder::class)
            ->disableOriginalConstructor()
            ->getMock();
        $finder
            ->method('files')
            ->willReturnSelf();
        $finder
            ->method('in')
            ->willReturnSelf();
        $finder
            ->method('name')
            ->willReturn($files);
        return $finder;
    }

    /**
     * @test
     */
    public function it_returns_non_zero_exit_code()
    {
        $meter = $this->getMockBuilder(DensityMeterInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $meter->expects(self::once())->method('calculate')->willReturn(1.0);
        $command = new DensityCommand($meter, $this->makeFinder(['some_file']));
        $this->assertSame(1, $command->run(new ArrayInput([
            'directories' => ['some_dir'],
            '--non-zero-exit-on-violation' => '1',
        ]), new NullOutput()));
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The "some_dir" directory does not exist.
     */
    public function it_fails_if_no_directory_exists()
    {
        $command = new DensityCommand(new DensityMeter(80), new Finder());
        $this->assertTrue($command->run(new ArrayInput(['directories' => ['some_dir']]), new NullOutput()));
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Threshold should be between 0 and 1
     */
    public function it_checks_threshold_option()
    {
        $command = new DensityCommand(new DensityMeter(80), new Finder());
        $this->assertTrue($command->run(new ArrayInput([
            'directories' => ['some_dir'],
            '--threshold' => '123',
        ]), new NullOutput()));
    }
}
