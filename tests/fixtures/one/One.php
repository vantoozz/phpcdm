<?php

use Exception;
use IteratorIterator;
use DateTimeImmutable;

/**
 * Class One
 */
final class One
{
    /**
     * @var int
     */
    private $one;

    /**
     * @var string
     */
    private $two;

    /**
     * @var bool
     */
    private $three;

    /**
     * @return int
     */
    public function getOne()
    {
        return $this->one;
    }

    /**
     * @param int $one
     */
    public function setOne($one)
    {
        $this->one = $one;
    }

    /**
     * @return string
     */
    public function getTwo()
    {
        return $this->two;
    }

    /**
     * @param string $two
     */
    public function setTwo($two)
    {
        $this->two = $two;
    }

    /**
     * @return bool
     */
    public function isThree()
    {
        return $this->three;
    }

    /**
     * @param bool $three
     */
    public function setThree($three)
    {
        $this->three = $three;
    }
}