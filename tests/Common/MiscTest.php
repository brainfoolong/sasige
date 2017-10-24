<?php

namespace Nullix\Tests\Common;

use Nullix\Sasige\File;
use PHPUnit\Framework\TestCase;

final class MiscTest extends TestCase
{
    public function testMethods()
    {
        $this->assertEquals([], File::getFiles("FOO"));
        File::deleteDirectory(SASIGE_PROJECT_ROOT . "/public");
    }
}
