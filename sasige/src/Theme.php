<?php

namespace Nullix\Sasige;

use Leafo\ScssPhp\Compiler;

/**
 * Hold information about the current theme context during generation
 */
class Theme
{

    /**
     * Cache filenames
     * @var string
     */
    private static $cacheFilenames;

    /**
     * Package data
     * @var array
     */
    private static $package;

    /**
     * Get package.json data
     * @return array
     */
    public static function getPackageData()
    {
        if (self::$package !== null) {
            return self::$package;
        }
        $path = SASIGE_PROJECT_ROOT . "/" . Config::getThemeFolder() . "/package.json";
        self::$package = json_decode(file_get_contents($path), true);
        return self::$package;
    }

    /**
     * Get a value for an option
     * @param string $key
     * @return mixed
     * @throws Exception
     */
    public static function getOption($key)
    {
        $data = self::getPackageData();
        if (!isset($data["option"][$key])) {
            throw new Exception("Package.json does not contain an option with key '$key'");
        }
        return $data["option"][$key];
    }

    /**
     * Get a translation value
     * @param string $key
     * @param string|null $language If null than it is the current page language
     * @return mixed
     * @throws Exception
     */
    public static function getTranslation($key, $language = null)
    {
        $language = $language === null ? Page::getCurrent()->getLanguage() : $language;
        $data = self::getPackageData();
        if (!isset($data["translation"][$language][$key])) {
            throw new Exception("Package.json does not contain a translation with language/key '$language' -> '$key'");
        }
        return $data["translation"][$language][$key];
    }

    /**
     * Get generated filename for a set of files
     * @param array $files
     * @param string $group css or js
     * @return string
     */
    public static function getGeneratedFilename(array $files, $group)
    {
        $hash = md5(implode("\n", $files));
        if (isset(self::$cacheFilenames[$hash])) {
            return self::$cacheFilenames[$hash];
        }
        $themeFolder = SASIGE_PROJECT_ROOT . "/" . Config::getThemeFolder() . "/";
        $content = "";
        // compile scss
        if ($group === "css") {
            $scssContent = "";
            $cssContent = "";
            foreach ($files as $file) {
                $path = $themeFolder . $file;
                if (preg_match("~\.scss$~i", $file)) {
                    $scssContent .= file_get_contents($path);
                } else {
                    $cssContent .= file_get_contents($path);
                }
            }
            if ($scssContent) {
                $scssContent = '
                    $themeUrl: "../sasige-theme/";
                    $publicRootUrl: "../";
                ' . $scssContent;
                require_once dirname(__DIR__) . "/lib/scssphp/scss.inc.php";
                $scss = new Compiler();
                $scssContent = $scss->compile($scssContent);
            }
            $content = $scssContent . $cssContent;
        }
        if ($group == "js") {
            foreach ($files as $file) {
                $path = $themeFolder . $file;
                $content .= file_get_contents($path);
            }
        }
        $filename = "sasige-" . md5($content) . "." . $group;
        self::$cacheFilenames[$hash] = $filename;
        $publicDir = SASIGE_PROJECT_ROOT . "/" . Config::getOutputFolder() . "/sasige-generated";
        File::createDirectoryRecursive($publicDir);
        file_put_contents($publicDir . "/" . $filename, $content);
        return $filename;
    }
}
