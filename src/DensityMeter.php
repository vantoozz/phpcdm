<?php

namespace Vantoozz\Density;

/**
 * Class BaseDensityMeter
 * @package Vantoozz\Density\DensityMeter
 */
final class DensityMeter
{
    /**
     * @var int
     */
    private $pageWidth;

    /**
     * BaseDensityMeter constructor.
     * @param $width
     */
    public function __construct($width)
    {
        $this->pageWidth = (int)$width;
    }

    /**
     * @param mixed $pageWidth
     */
    public function setPageWidth($pageWidth)
    {
        $this->pageWidth = (int)$pageWidth;
    }

    /**
     * @param string $file
     * @return float
     * @throws \RuntimeException
     */
    public function calculate($file)
    {
        $handler = fopen($file, 'rb');
        if (!$handler) {
            throw new \RuntimeException('Cannot read file: ' . $file);
        }

        $chars = 0;
        $lines = 0;

        while (($line = fgets($handler)) !== false) {
            $chars += $this->charsCount($line);
            $lines++;
        }

        return min(1.0, $chars / ($lines * $this->pageWidth));
    }

    /**
     * @param string $line
     * @return int
     */
    private function charsCount($line)
    {
        $line = trim($line);

        if (0 === stripos($line, 'use ')) {
            return 0;
        }

        return mb_strlen(str_replace([' ', "\t"], '', $line));
    }
}
