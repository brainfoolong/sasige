<?php

use Nullix\Sasige\Config;

// default autoloader
spl_autoload_register(function ($class) {

    $mappings = [
        'Nullix\\Sasige\\' => [__DIR__ . '/'],
        '' => [__DIR__ . "/../lib/parsedown/"]
    ];
    foreach ($mappings as $prefix => $baseDirs) {
        foreach ($baseDirs as $baseDir) {
            // does the class use the namespace prefix?
            $len = strlen($prefix);
            if (strncmp($prefix, $class, $len) !== 0) {
                continue;
            }

            // get the relative class name
            $relative_class = substr($class, $len);

            // replace the namespace prefix with the base directory, replace namespace
            // separators with directory separators in the relative class name, append
            // with .php
            $file = $baseDir . str_replace('\\', '/', $relative_class) . '.php';

            // if the file exists, require it
            if (file_exists($file)) {
                require $file;
                return;
            }
        }
    }
});

// second autoloader for the dynamic configurable theme and src directory
spl_autoload_register(function ($class) {
    $mappings = [
        'Nullix\\Sasige\\' => [
            __DIR__ . '/../../' . Config::getThemeFolder() . '/src/',
            __DIR__ . '/../../' . Config::getAdditionalSrcFolder() . "/"
        ],
    ];

    foreach ($mappings as $prefix => $baseDirs) {
        foreach ($baseDirs as $baseDir) {
            // does the class use the namespace prefix?
            $len = strlen($prefix);
            if (strncmp($prefix, $class, $len) !== 0) {
                continue;
            }

            // get the relative class name
            $relative_class = substr($class, $len);

            // replace the namespace prefix with the base directory, replace namespace
            // separators with directory separators in the relative class name, append
            // with .php
            $file = $baseDir . str_replace('\\', '/', $relative_class) . '.php';

            // if the file exists, require it
            if (file_exists($file)) {
                require $file;
                return;
            }
        }
    }
});
