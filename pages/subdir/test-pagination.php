<?php

namespace Nullix\Sasige;

$page = Page::getCurrent();
$page->setPageTitle("Sasige - Static Site Generator");
$page->setLeadText("Simple, lightning fast, text to websites and blogs.");
$page->setDate("now");
$page->setLabel("Home");
$page->setSort(2);
$page->setTags(["site", "navigation"]);
$page->setThemeTemplate("site");
$page->setCustomProperty("foo", "bar");


$pageset = new Pageset();
$pageset->includePagesByTags(["navigation"]);
$pageset->filterPagesByLanguage($page->getLanguage());
$pageset->setOrderBy("-date");

$pagination = new Pagination($page, $pageset);
$pagination->setEntriesPerPage(5);

$page->setContentByCallable(function (Page $page) {
    $pagination = $page->getPagination();
    echo $pagination->getNumericPageHtmlList();
    echo $page->getCustomProperty("foo");
});


