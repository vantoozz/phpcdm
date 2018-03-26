<?php

namespace Vantoozz\PHPCDM\UnitTests;

/**
 * Class BadFileStream
 * @package Vantoozz\PHPCDM\UnitTests
 */
final class BadFileStream
{
    /**
     * @return bool
     */
    public function stream_open()
    {
        return false;
    }

    /**
     * @return array
     */
    public function url_stat()
    {
        return [
            'dev' => 0,
            'ino' => 0,
            'mode' => 777,
            'nlink' => 0,
            'uid' => 'user',
            'gid' => 'user',
            'rdev' => 0,
            'size' => 10,
            'atime' => 111,
            'mtime' => 111,
            'ctime' => 111,
            'blksize' => -1,
            'blocks' => -1,
        ];
    }
}