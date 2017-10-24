<?php
namespace Nullix\Tests\Common;

use Nullix\Sasige\PostHelper;
use PHPUnit\Framework\TestCase;

final class AutoloadTest extends TestCase
{

    public function testAdditionalSrc()
    {

        $this->assertEquals("Nullix\Sasige\PostHelper", PostHelper::class);
    }
}
