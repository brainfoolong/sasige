<?php

namespace Nullix\Tests\Common;

use Nullix\Sasige\Config;
use Nullix\Sasige\Exception;
use Nullix\Sasige\Generator;
use PHPUnit\Framework\TestCase;

final class GeneratorTest extends TestCase
{
    public function testBuild()
    {
        Generator::build();
        $this->assertTrue(true);
    }

    public function testBuildNoIndex()
    {
        Config::setHideIndexHtmlInUrls(true);
        Generator::build();
        $this->assertTrue(true);
    }

    public function testException1()
    {
        $this->expectException(Exception::class);
        Config::setDefaultLanguage(false);
        Generator::build();
    }

    public static function tearDownAfterClass()
    {
        Config::setDefaultLanguage("en");
    }
}
