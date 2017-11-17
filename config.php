<?php
/**
 * Basic config file
 */

use Nullix\Sasige\Config;

Config::setDefaultLanguage("en"); // if you do not define a language in the page than this is the default
Config::setThemeFolder("theme"); // the themes root directory
Config::setOutputFolder("public"); // where to put the generated website files
Config::setPagesFolder("pages"); // folder that contains the pages files
Config::setStaticFolder("static"); // folder that contains the static files that will be merged into the output folder
Config::setHideIndexHtmlInUrls(false); // if true than the string "index.html" will be removed when generate page links
Config::setServerPort(4433); // server port when use "sasige serve"
