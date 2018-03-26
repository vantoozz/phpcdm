<?php

namespace Vantoozz\PHPCDM;

/**
 * Class BaseDensityMeter
 * @package Vantoozz\PHPCDM\DensityMeter
 */
final class DensityMeter
{
    /**
     * @var int
     */
    private $pageWidth;

    /**
     * BaseDensityMeter constructor.
     * @param int $width
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

        if (!file_exists($file)) {
            throw new \RuntimeException('No such file: ' . $file);
        }

        $handler = fopen($file, 'rb');
        if (!$handler) {
            throw new \RuntimeException('Cannot read file: ' . $file);
        }

        $chars = 0;
        $lines = 0;

        while (($line = fgets($handler)) !== false) {
            $line = trim($line);

            if (0 === stripos($line, 'use ')) {
                continue;
            }

            $chars += mb_strlen(str_replace([' ', "\t"], '', $line));
            $lines++;
        }

        $area = $lines * $this->pageWidth;

        if (0 === $area) {
            return 0.0;
        }

        $density = $chars / $area;

        if (1 < $density) {
            $density = 1.0;
        }

        return $density;
    }
}
