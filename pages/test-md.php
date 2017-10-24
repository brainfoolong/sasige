<?php

namespace Nullix\Sasige;

$page = Page::getCurrent();
$page->setPageTitle("Sasige - Static Site Generator");
$page->setLeadText("Simple, lightning fast, text to websites and blogs.");
$page->setDate("2017-01-01");
$page->setLabel("Test");
$page->setSort(2);
$page->setTags(["site", "navigation"]);
$page->setThemeTemplate("site");
$page->setContentByMarkdownFileSelf();
