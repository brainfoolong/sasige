<?php

namespace Nullix\Sasige;

require __DIR__ . "/../sasige/src/_init.php";

$sourceDir = File::concat(File::sanitizePath(dirname(__DIR__)), "sasige");
$destDir = File::concat(File::sanitizePath(__DIR__), "tmp");
$files = File::getFiles($sourceDir, null, true);
$releaseFile = "release-" . SASIGE_VERSION . ".zip";

$copyFiles = [];

foreach ($files as $file) {
    $relativePath = str_replace($sourceDir, "", $file);
    $dest = $destDir . $relativePath;
    $copyFiles[$file] = $dest;
}

File::copyFiles($copyFiles);

exec("cd " . escapeshellarg($destDir) . " && zip -r -u $releaseFile *");
copy($destDir . "/$releaseFile", __DIR__ . "/$releaseFile");
File::deleteDirectory($destDir, true);
