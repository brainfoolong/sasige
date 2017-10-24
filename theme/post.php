<?php

namespace Nullix\Sasige;

$page = Page::getCurrent();
require __DIR__ . "/header.php";
?>
    <div class="content">
        <time datetime="<?= $page->getDate()->format("Y-m-d") ?>" pubdate>Last updated <?= $page->getDate()->format("d.m.Y") ?>
        </time>
        <div class="post">
            <?= $page->getContent() ?>
        </div>
    </div>
    <?php

require __DIR__ . "/footer.php";
