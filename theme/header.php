<?php
namespace Nullix\Sasige;

$page = Page::getCurrent();

$pageset = new Pageset();
$pageset->includePagesByTags(["navigation"]);
$pageset->filterPagesByLanguage($page->getLanguage());
$pageset->setOrderBy("+sort");

$leadText = $page->getLeadText();

?>
<!DOCTYPE html>
<html lang="<?= $page->getLanguage() ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="<?= $page->getLeadText() ?>">
    <meta name="author" content="Sasige">
    <title><?= $page->getPageTitle() ?></title>
    <link href="<?= $page->getThemeCssUrl(["stylesheets/page.scss"]) ?>" rel="stylesheet">
</head>
<body>
<nav>
    <div class="screen-small">
        <div class="logo">
            <div class="icon"><img src="<?= $page->getPublicRootUrl("logo.png") ?>" alt="logo"></div>
            <div class="menu" onclick="document.getElementById('mobilemenu').classList.toggle('active')"><img
                        src="<?= $page->getThemeUrl("ic_menu_white_24px.svg") ?>"
                        alt="<?= Theme::getTranslation("menu") ?>"></div>
        </div>
        <div id="mobilemenu">
            <?php
            $pageset = new Pageset();
            $pageset->includePagesByTags(Theme::getOption("navigationPagesetTags"));
            $pageset->filterPagesByLanguage($page->getLanguage());
            $pageset->setOrderBy("+sort");
            echo $pageset->getHtmlListRecursive();
            ?>
        </div>
    </div>
    <div class="screen-big">
        <div class="logo">
            <div class="icon"><img src="<?= $page->getPublicRootUrl("logo.png") ?>" alt="logo"></div>
        </div>
        <?php
        $pageset = new Pageset();
        $pageset->includePagesByTags(Theme::getOption("navigationPagesetTags"));
        $pageset->filterPagesByLanguage($page->getLanguage());
        $pageset->setOrderBy("+sort");
        echo $pageset->getHtmlListRecursive();
        ?>
    </div>
</nav>

<div class="page">
    <header>
        <h1 class="pagetitle"><?= $page->getPageTitle() ?>
            <?php if ($leadText) { ?>
                <div class="leadtext"><?= $leadText ?></div>
            <?php } ?>
        </h1>
        <div class="divider"></div>
    </header>