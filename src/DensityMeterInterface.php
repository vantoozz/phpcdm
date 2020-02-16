<?php

namespace Vantoozz\PHPCDM;

/**
 * Interface DensityMeterInterface
 * @package Vantoozz\PHPCDM
 */
interface DensityMeterInterface
{
    /**
     * @param mixed $pageWidth
     */
    public function setPageWidth($pageWidth);

    /**
     * @param string $file
     * @return float
     * @throws \RuntimeException
     */
    public function calculate($file);
}
