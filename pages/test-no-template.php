<?php

namespace Nullix\Sasige;

$page = Page::getCurrent();
$page->setPageTitle("Sasige - Static Site Generator");
$page->setLeadText("Simple, lightning fast, text to websites and blogs.");
$page->setDate("now");
$page->setLabel("Home");
$page->setSort(2);
$page->setTags(["site", "navigation"]);
