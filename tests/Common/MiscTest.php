<?php

namespace Nullix\Tests\Common;

use Nullix\Sasige\Config;
use Nullix\Sasige\File;
use PHPUnit\Framework\TestCase;

final class MiscTest extends TestCase
{
    public function testMethods()
    {
        $this->assertEquals([], File::getFiles("FOO"));
        File::deleteDirectory(SASIGE_PROJECT_ROOT . "/public");

        Config::setStaticFolder("static");
        $this->assertEquals("static", Config::getStaticFolder());

        Config::setThemeFolder("theme");
        $this->assertEquals("theme", Config::getThemeFolder());

        Config::setOutputFolder("public");
        $this->assertEquals("public", Config::getOutputFolder());

        Config::setPagesFolder("pages");
        $this->assertEquals("pages", Config::getPagesFolder());

        Config::setServerPort(4433);
        $this->assertEquals(4433, Config::getServerPort());
    }
}
