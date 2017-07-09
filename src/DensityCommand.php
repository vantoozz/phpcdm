<?php

namespace Vantoozz\Density;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

/**
 * Class DensityCommand
 * @package Vantoozz\Density\Console
 */
final class DensityCommand extends Command
{
    const COMMAND_NAME = 'analyze';

    /**
     * @var DensityMeter
     */
    private $densityMeter;

    /**
     * @var Finder
     */
    private $finder;

    /**
     * @var float
     */
    private $threshold;

    /**
     * DensityCommand constructor.
     * @param DensityMeter $densityMeter
     * @param Finder $finder
     * @param float $threshold
     * @throws \Symfony\Component\Console\Exception\LogicException
     */
    public function __construct(DensityMeter $densityMeter, Finder $finder, $threshold)
    {
        parent::__construct();
        $this->densityMeter = $densityMeter;
        $this->finder = $finder;
        $this->threshold = $threshold;
    }

    /**
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function configure()
    {
        $this
            ->setName(self::COMMAND_NAME)
            ->setDescription('Meter source code density')
            ->setHelp('Meter source code density')
            ->setDefinition(
                new InputDefinition([
                    new InputArgument('directories', InputArgument::REQUIRED | InputArgument::IS_ARRAY),
                ])
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $directories = $input->getArgument('directories');
        if (!is_array($directories)) {
            throw new InvalidArgumentException('Not enough arguments');
        }

        $failed = false;
        /** @noinspection ForeachSourceInspection */
        foreach ($this->finder->files()->in($directories)->name('*.php') as $file) {
            $density = $this->densityMeter->calculate($file);
            if ($density >= $this->threshold) {
                $output->writeln('<info>' . $file . ' => ' . $density . '</info>');
                $failed = true;
            }
        }

        return $failed ? 1 : 0;
    }
}
