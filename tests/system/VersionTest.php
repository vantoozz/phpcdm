<?php

namespace Vantoozz\PHPCDM\SystemTests;

use PHPUnit\Framework\TestCase;
use Vantoozz\PHPCDM\Metadata;

/**
 * Class VersionTest
 * @package Vantoozz\PHPCDM\SystemTests
 */
final class VersionTest extends TestCase
{
    /**
     * @test
     */
    public function it_has_versions_synced_up_across_the_app()
    {
        $tag = exec('cd ' . __DIR__ . ' && git describe --tags --abbrev=0');
        $tag = ltrim($tag, 'v');

        $this->assertSame($tag, Metadata::VERSION);
    }
}