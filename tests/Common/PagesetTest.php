<?php

namespace Nullix\Tests\Common;

use Nullix\Sasige\Exception;
use Nullix\Sasige\Generator;
use Nullix\Sasige\Page;
use Nullix\Sasige\Pageset;
use PHPUnit\Framework\TestCase;

final class PagesetTest extends TestCase
{

    public static function setUpBeforeClass()
    {
        Generator::build();
    }

    public function testTags()
    {


        $pageset = new Pageset();
        $pageset->includePagesByTags(["navigation"]);

        $this->assertGreaterThan(0, count($pageset->getPages()));

        $pageset = new Pageset();
        $pageset->includePagesByTags(["navigation"]);
        $pageset->excludePagesByTags(["navigation"]);
        $this->assertEquals([], $pageset->getPages());

        $pageset = new Pageset();
        $pageset->includePagesByTags(["navigation"]);
        $pages = $pageset->getPages();

        $pageset->filterPagesByTags(["navigation"]);
        $this->assertEquals($pages, $pageset->getPages());
    }

    public function testRegex()
    {
        $pageset = new Pageset();
        $pageset->includePagesByRegex("~subdir/test-html-string~");

        $this->assertEquals(1, count($pageset->getPages()));

        $pageset = new Pageset();
        $pageset->includePagesByRegex("~subdir/test-html-string~");
        $pageset->excludePagesByRegex("~subdir/test-html-string~");
        $this->assertEquals([], $pageset->getPages());

        $pageset = new Pageset();
        $pageset->includePagesByRegex("~subdir/test-html-string~");
        $pages = $pageset->getPages();
        $pageset->filterPagesByRegex("~subdir/test-html-string~");
        $this->assertEquals($pages, $pageset->getPages());
    }

    public function testLanguage()
    {
        $pageset = new Pageset();
        $pageset->includePagesByLanguage("en");

        $this->assertGreaterThan(0, count($pageset->getPages()));

        $pageset = new Pageset();
        $pageset->includePagesByLanguage("en");
        $pageset->excludePagesByLanguage("en");
        $this->assertEquals([], $pageset->getPages());

        $pageset = new Pageset();
        $pageset->includePagesByLanguage("en");
        $pages = $pageset->getPages();
        $pageset->filterPagesByLanguage("en");
        $this->assertEquals($pages, $pageset->getPages());
    }

    public function testManual()
    {
        $pageset = new Pageset();
        $pageset->includePages("index");

        $this->assertEquals(1, count($pageset->getPages()));

        $pageset = new Pageset();
        $pageset->includePages("index");
        $pageset->excludePages("index");
        $this->assertEquals([], $pageset->getPages());

        $pageset = new Pageset();
        $pageset->includePages("index");
        $pages = $pageset->getPages();
        $pageset->filterPages("indexs");
        $this->assertEquals([], $pageset->getPages());
    }

    public function testSort()
    {
        $pageset = new Pageset();
        $pageset->includePages("index");
        $pageset->includePages("test-md");
        $pageset->setOrderBy("+date");
        $pages = $pageset->getPages();
        $this->assertEquals([2, 1], array_map(function (Page $page) {
            return $page->getSort();
        }, $pages));

        $pageset = new Pageset();
        $pageset->includePages("index");
        $pageset->includePages("test-md");
        $pageset->setOrderBy("-date");
        $pages = $pageset->getPages();
        $this->assertEquals([1, 2], array_map(function (Page $page) {
            return $page->getSort();
        }, $pages));


        $pageset = new Pageset();
        $pageset->includePages("index");
        $pageset->includePages("test-md");
        $pageset->setOrderBy("+label");
        $pages = $pageset->getPages();
        $this->assertEquals([1, 2], array_map(function (Page $page) {
            return $page->getSort();
        }, $pages));

        $pageset = new Pageset();
        $pageset->includePages("index");
        $pageset->includePages("test-md");
        $pageset->setOrderBy("-label");
        $pages = $pageset->getPages();
        $this->assertEquals([2, 1], array_map(function (Page $page) {
            return $page->getSort();
        }, $pages));

        $pageset = new Pageset();
        $pageset->includePages("index");
        $pageset->includePages("test-md");
        $pageset->setOrderBy("+sort");
        $pages = $pageset->getPages();
        $this->assertEquals([1, 2], array_map(function (Page $page) {
            return $page->getSort();
        }, $pages));

        $pageset = new Pageset();
        $pageset->includePages("index");
        $pageset->includePages("test-md");
        $pageset->setOrderBy("-sort");
        $pages = $pageset->getPages();
        $this->assertEquals([2, 1], array_map(function (Page $page) {
            return $page->getSort();
        }, $pages));
    }

    public function testException1()
    {
        $this->expectException(Exception::class);
        $pageset = new Pageset();
        $pageset->includePages("index");
        $pageset->setOrderBy("date");
        $pageset->getPages();
    }
}
