<?php
namespace Nullix\Tests\Common;

use Nullix\Sasige\Console;
use Nullix\Sasige\Generator;
use PHPUnit\Framework\TestCase;

final class ConsoleTest extends TestCase
{

    public function testDefault()
    {
        $_SERVER["argv"][1] = "";
        require __DIR__ . "/../../sasige/bin/sasige.php";
        $this->assertTrue(true);
    }

    public function testWatch()
    {
        $_SERVER["argv"][1] = "watch";
        require __DIR__ . "/../../sasige/bin/sasige.php";
        $this->assertTrue(true);
    }

    public function testServe()
    {
        $_SERVER["argv"][1] = "serve";
        require __DIR__ . "/../../sasige/bin/sasige.php";
        $this->assertTrue(true);
    }

    public function testBuild()
    {
        $_SERVER["argv"][1] = "build";
        require __DIR__ . "/../../sasige/bin/sasige.php";
        $this->assertTrue(true);
    }
}
