<?php

namespace Nullix\Sasige;

/**
 * Class Config
 */
class Config
{

    /**
     * The default language
     * @var string
     */
    private static $defaultLanguage;

    /**
     * Hide index.html in urls
     * @var bool
     */
    private static $hideIndexHtmlInUrls;

    /**
     * The port to bind the debug server to
     * @var int
     */
    private static $serverPort;

    /**
     * The folder where the theme files are in
     * @var string
     */
    private static $themeFolder;

    /**
     * The folder where the static files are in
     * @var string
     */
    private static $staticFolder;

    /**
     * The folder where the pages files are in
     * @var string
     */
    private static $pagesFolder;

    /**
     * The folder to put the generated public files in
     * @var string
     */
    private static $outputFolder;

    /**
     * The folder for the additional src files to include
     * @var string
     */
    private static $additionalSrcFolder;

    /**
     * Get additional src folder
     * @return string
     */
    public static function getAdditionalSrcFolder()
    {
        return self::$additionalSrcFolder;
    }

    /**
     * Set additional src folder
     * @param string $additionalSrcFolder
     */
    public static function setAdditionalSrcFolder($additionalSrcFolder)
    {
        self::$additionalSrcFolder = $additionalSrcFolder;
    }


    /**
     * Get theme folder
     * @return string
     */
    public static function getThemeFolder()
    {
        return self::$themeFolder;
    }

    /**
     * Set theme folder
     * @param string $themeFolder
     */
    public static function setThemeFolder($themeFolder)
    {
        self::$themeFolder = $themeFolder;
    }

    /**
     * Get static folder
     * @return string
     */
    public static function getStaticFolder()
    {
        return self::$staticFolder;
    }

    /**
     * Set static folder
     * @param string $staticFolder
     */
    public static function setStaticFolder($staticFolder)
    {
        self::$staticFolder = $staticFolder;
    }

    /**
     * Get pages folder
     * @return string
     */
    public static function getPagesFolder()
    {
        return self::$pagesFolder;
    }

    /**
     * Set pages folder
     * @param string $pagesFolder
     */
    public static function setPagesFolder($pagesFolder)
    {
        self::$pagesFolder = $pagesFolder;
    }

    /**
     * Get output folder
     * @return string
     */
    public static function getOutputFolder()
    {
        return self::$outputFolder;
    }

    /**
     * Set the output folder
     * @param string $outputFolder
     */
    public static function setOutputFolder($outputFolder)
    {
        self::$outputFolder = $outputFolder;
    }

    /**
     * Get the server port
     * @return int
     */
    public static function getServerPort()
    {
        return self::$serverPort;
    }

    /**
     * Set the server port
     * @param int $serverPort
     */
    public static function setServerPort($serverPort)
    {
        self::$serverPort = $serverPort;
    }

    /**
     * Get the default language
     * @return string
     */
    public static function getDefaultLanguage()
    {
        return self::$defaultLanguage;
    }

    /**
     * Set the default language
     * @param string $defaultLanguage
     */
    public static function setDefaultLanguage($defaultLanguage)
    {
        self::$defaultLanguage = $defaultLanguage;
    }

    /**
     * Get hide index in html urls
     * @return bool
     */
    public static function getHideIndexHtmlInUrls()
    {
        return self::$hideIndexHtmlInUrls;
    }

    /**
     * Set true you want to hide index.html in generated page urls
     * You will than get a url like: http://host/mypage/ instead of http://host/mypage/index.html
     * Most servers will have the index.html as default folder index, so it can be ommited
     * @param bool $hideIndexHtmlInUrls
     */
    public static function setHideIndexHtmlInUrls($hideIndexHtmlInUrls)
    {
        self::$hideIndexHtmlInUrls = $hideIndexHtmlInUrls;
    }

}
