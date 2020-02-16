<?php

namespace Vantoozz\PHPCDM;

use RuntimeException;

/**
 * Class DensityMeter
 * @package Vantoozz\PHPCDM
 */
final class DensityMeter implements DensityMeterInterface
{

    const SUPPRESS_INTENTION = '@SuppressWarnings(PHPCDM)';

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
     * @throws RuntimeException
     */
    public function calculate($file)
    {
        if (!file_exists($file)) {
            throw new RuntimeException('No such file: ' . $file);
        }

        $handler = fopen($file, 'rb');
        if (!$handler) {
            throw new RuntimeException('Cannot read file: ' . $file);
        }

        $chars = 0;
        $lines = 0;

        while (($line = fgets($handler)) !== false) {
            if ($this->isSuppressIntentionLine($line)) {
                return 0.0;
            }

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

    /**
     * @param string $line
     * @return bool
     */
    private function isSuppressIntentionLine($line)
    {
        return false !== stripos($line, self::SUPPRESS_INTENTION);
    }
}
