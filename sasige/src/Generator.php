<?php

namespace Nullix\Sasige;

/**
 * Class Generator
 */
class Generator
{
    /**
     * The current set of pages
     * @var Page[]
     */
    private static $pages;

    /**
     * Build all public files
     */
    public static function build()
    {
        Page::setInstances([]);

        $publicFolder = SASIGE_PROJECT_ROOT . "/" . Config::getOutputFolder();
        $pagesFolder = SASIGE_PROJECT_ROOT . "/" . Config::getPagesFolder();
        $staticFolder = SASIGE_PROJECT_ROOT . "/" . Config::getStaticFolder();

        // delete and recreate the whole public folder
        File::deleteDirectory($publicFolder, true);
        mkdir($publicFolder, 0777);

        $pagesDir = $pagesFolder;
        $files = File::getFiles($pagesDir, "~\.php$~i", true);
        if (!Config::getDefaultLanguage()) {
            throw new Exception("You must set a default language");
        }
        Console::writeStdout("###Build###\n\n");
        Console::writeStdout("===Html page preparation===\n");
        self::$pages = [];
        foreach ($files as $file) {
            $filename = basename($file);
            $directory = substr(dirname($file), mb_strlen($pagesDir) + 1);
            $page = Page::create();
            $page->setFullPath($file);
            if ($directory) {
                $page->setDirectory($directory);
            }
            $filename = substr($filename, 0, strrpos($filename, "."));
            $page->setFilename($filename);

            Console::writeStdout("Prepare page " . $page->getPath() . "...");
            Page::setCurrent($page);
            self::preparePage($page);

            if (!$page->getDate()) {
                Console::writeStdout("Skipped because no date has been set," .
                    " those pages will be considered as draft\n");
                continue;
            }
            Console::writeStdout(" Done\n");
            self::$pages[] = $page;
        }

        Console::writeStdout("\n");
        Console::writeStdout("===Html page generation===\n");

        foreach (self::$pages as $page) {
            Console::writeStdout("Generate page " . $page->getPath() . "...");
            $pagination = $page->getPagination();
            if ($pagination) {
                Console::writeStdout("\n");
            }

            $pageNumbers = $pagination ? $pagination->getTotalPages() : 1;
            for ($i = 1; $i <= $pageNumbers; $i++) {
                if ($pagination) {
                    $pagination->setCurrentPage($i);
                }

                Page::setCurrent($page);
                $content = self::getPageContent($page);

                $directory = File::concat($publicFolder, $page->getDirectory());
                File::createDirectoryRecursive($directory);
                $filename = $page->getFilenameToPageNr($i);
                file_put_contents($directory . "/" . $filename . ".html", $content);
                if ($pagination) {
                    Console::writeStdout(" - Pagination subpage $i ($filename)... Done\n");
                } else {
                    Console::writeStdout(" Done\n");
                }
            }
        }

        Console::writeStdout("Finished\n\n\n");

        Console::writeStdout("===Copy static folder files to output folder root===\n");
        $directory = $staticFolder;
        $files = File::getFiles($directory, null, true, false);
        $copyFiles = [];
        foreach ($files as $file) {
            $fileRelative = substr($file, mb_strlen($directory) + 1);
            $src = $file;
            $dest = $publicFolder . "/" . $fileRelative;
            $copyFiles[$src] = $dest;
        }
        File::copyFiles($copyFiles);
        Console::writeStdout("Finished\n\n\n");

        Console::writeStdout("===Copy theme's static folder to sasige-theme folder===\n");
        $directory = SASIGE_PROJECT_ROOT . "/" . Config::getThemeFolder() . "/static";
        $files = File::getFiles($directory, null, true, false);
        $copyFiles = [];
        foreach ($files as $file) {
            $fileRelative = substr($file, mb_strlen($directory) + 1);
            $src = $file;
            $dest = $publicFolder . "/sasige-theme/" . $fileRelative;
            $copyFiles[$src] = $dest;
        }
        File::copyFiles($copyFiles);
        Console::writeStdout("Finished\n\n\n");

        Console::writeStdout("###Build complete###\n\n\n");
    }

    /**
     * Prepare the page
     * @param Page $page
     */
    private static function preparePage(Page $page)
    {
        require $page->getFullPath();
    }

    /**
     * Get page content
     *
     * @param Page $page
     * @return string
     */
    private static function getPageContent(Page $page)
    {
        if ($page->getThemeTemplate()) {
            ob_start();
            require SASIGE_PROJECT_ROOT . "/theme/" . $page->getThemeTemplate() . ".php";
            $content = ob_get_contents();
            ob_end_clean();
            return $content;
        } else {
            return $page->getContent();
        }
    }
}
