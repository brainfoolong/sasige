<?php

namespace Nullix\Tests\Common;

use Nullix\Sasige\Exception;
use Nullix\Sasige\Theme;
use PHPUnit\Framework\TestCase;

final class ThemeTest extends TestCase
{

    public function testPackage()
    {
        $this->assertEquals("Menu", Theme::getTranslation("menu", "en"));
    }

    public function testException1()
    {
        $this->expectException(Exception::class);
        Theme::getOption("foo");
    }

    public function testException2()
    {
        $this->expectException(Exception::class);
        Theme::getOption("foos");
    }

    public function testException3()
    {
        $this->expectException(Exception::class);
        Theme::getTranslation("foo");
    }
}
