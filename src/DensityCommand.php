<?php

namespace Vantoozz\PHPCDM;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

/**
 * Class DensityCommand
 * @package Vantoozz\PHPCDM\Console
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
final class DensityCommand extends Command
{
    const COMMAND_NAME = 'analyze';
    const EXIT_CODE_SUCCESS = 0;
    const EXIT_CODE_FAILURE = 1;

    /**
     * @var DensityMeter
     */
    private $densityMeter;

    /**
     * @var Finder
     */
    private $finder;

    /**
     * DensityCommand constructor.
     * @param DensityMeter $densityMeter
     * @param Finder $finder
     * @throws \Symfony\Component\Console\Exception\LogicException
     */
    public function __construct(DensityMeter $densityMeter, Finder $finder)
    {
        parent::__construct();
        $this->densityMeter = $densityMeter;
        $this->finder = $finder;
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
                    new InputArgument(
                        'directories',
                        InputArgument::REQUIRED | InputArgument::IS_ARRAY
                    ),

                    new InputOption(
                        'non-zero-exit-on-violation',
                        null,
                        InputOption::VALUE_NONE | InputOption::VALUE_OPTIONAL,
                        'Return a non zero exit code on violation'
                    ),

                    new InputOption(
                        'threshold',
                        'T',
                        InputOption::VALUE_OPTIONAL,
                        'Max allowed density',
                        Defaults::THRESHOLD
                    ),

                    new InputOption(
                        'page-width',
                        'P',
                        InputOption::VALUE_OPTIONAL,
                        'Page width',
                        Defaults::PAGE_WIDTH
                    ),
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

        $this->densityMeter->setPageWidth((int)$input->getOption('page-width'));

        $threshold = (float)$input->getOption('threshold');
        if (0 >= $threshold || 1 <= $threshold) {
            throw new InvalidArgumentException('Threshold should be between 0 and 1');
        }

        $failed = false;
        /** @noinspection ForeachSourceInspection */
        foreach ($this->finder->files()->in($directories)->name('*.php') as $file) {
            $density = $this->densityMeter->calculate($file);
            if ($density >= $threshold) {
                $output->writeln('<info>' . $file . ' => ' . $density . '</info>');
                $failed = true;
            }
        }

        if ($failed && $input->getOption('non-zero-exit-on-violation')) {
            return self::EXIT_CODE_FAILURE;
        }

        return self::EXIT_CODE_SUCCESS;
    }
}
