<?php

namespace Nullix\Sasige;

require __DIR__ . "/../sasige/src/_init.php";

$ignoreFiles = [
    "^/\.git(/|$)",
    "^/\.idea(/|$)",
    "^/dev(/|$)",
    "^/public/.+?",
    "^/static/.+?"
];
$sourceDir = File::concat(File::sanitizePath(dirname(__DIR__)));
$destDir = File::concat(File::sanitizePath(__DIR__), "tmp");
$files = File::getFiles($sourceDir, null, true, true);
$releaseFile = "release-" . SASIGE_VERSION . ".zip";

$copyFiles = [];

foreach ($files as $file) {
    $relativePath = str_replace($sourceDir, "", $file);
    foreach ($ignoreFiles as $regex) {
        if (preg_match("~$regex~i", $relativePath)) {
            continue 2;
        }
    }
    $dest = $destDir . $relativePath;
    $copyFiles[$file] = $dest;
}

File::copyFiles($copyFiles);

exec("cd " . escapeshellarg($destDir) . " && zip -r -u $releaseFile *");
copy($destDir . "/$releaseFile", __DIR__ . "/$releaseFile");
File::deleteDirectory($destDir, true);
