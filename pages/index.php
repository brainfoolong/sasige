<?php

namespace Nullix\Sasige;

$page = Page::getCurrent();
$page->setPageTitle("Sasige - Static Site Generator");
$page->setLeadText("Simple, lightning fast, text to websites and blogs.");
$page->setDate("2017-01-02");
$page->setLabel("Home");
$page->setSort(1);
$page->setTags(["site", "navigation"]);
$page->setThemeTemplate("site");
$page->setContentByCallable(function (Page $page) {
    $command = [
        '$ sudo apt-get install php-cli',
        '$ mkdir sasige-website && cd sasige-website',
        '$ wget urlexample -O sasige.zip && unzip sasige.zip && rm sasige.zip',
        '$ ./sasige/bin/sasige build',
        '$ ./sasige/bin/sasige serve',
        '# => Now look into folder public or use following command',
        '$ ./sasige/bin/sasige serve',
        '# => Now browse to http://localhost:4433'
    ];
    ?>
    <div class="console">
        <div class="header">Quick start linux with dependencies</div>
        <div class="window"><?= implode("\n", $command) ?></div>
    </div>
    <h2>Simple & Secure</h2>
    <p>Because Sasige just generate static files, you can upload it to every hosting provider.
        No backdoors, no database needed, no security updates required, just static files that last forever.</p>

    <h2>Content as you wish</h2>
    <p>Sasige give you different tools to write your content. Pure html, markdown, plaintext or
        powerful advanced PHP language.</p>

    <h2>Like a static blog cms</h2>
    <p>THE tool to write just in blog style. With pagination, categories, pages, sites, drafts,
        themes, etc...</p>

    <h2>Automatic deployment</h2>
    <p>Everything can be automated and Sasige works perfectly fine with continues integration services.</p>

    <?php
});