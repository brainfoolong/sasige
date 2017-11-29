<?php

use Nullix\Sasige\File;

if (php_sapi_name() !== "cli") {
    die("Only allowed in command line mode");
}

error_reporting(E_ALL);
set_time_limit(0);
ini_set("display_errors", "on");

require_once  __DIR__ . "/File.php";

define("SASIGE_PROJECT_ROOT", File::sanitizePath(dirname(dirname(__DIR__))));
define("SASIGE_BIN_ROOT", SASIGE_PROJECT_ROOT . "/sasige/bin");
define("SASIGE_VERSION", "0.3");

require_once  __DIR__ . "/_autoload.php";
require_once  __DIR__ . "/../../config.php";

set_error_handler(\Nullix\Sasige\Exception::class . "::errorHandler");
