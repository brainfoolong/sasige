<?php

namespace Nullix\Sasige;

/**
 * Class File
 */
class File
{
    /**
     * Copy files from src to dest
     * Array key is src
     * Array value is dest
     *
     * @param string[] $files
     */
    public static function copyFiles(array $files)
    {
        foreach ($files as $src => $dest) {
            $directory = dirname($dest);
            self::createDirectoryRecursive($directory);
            copy($src, $dest);
            Console::writeStdout("Copied " . $src . " to " . $dest . "\n");
        }
    }

    /**
     * Create a directory recursive if it doesn't exist yet
     *
     * @param string $directory
     */
    public static function createDirectoryRecursive($directory)
    {
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
    }

    /**
     * Concat filepath parts with a slash
     * @param array ...$parts
     * @return string
     */
    public static function concat(...$parts)
    {
        foreach ($parts as $key => $part) {
            $part = trim($part);
            if ($part === "") {
                unset($parts[$key]);
                continue;
            }
            $parts[$key] = $part;
        }
        return implode("/", $parts);
    }

    /**
     * Sanitize path
     * @param string $path
     * @param bool $realpath
     * @return string
     */
    public static function sanitizePath($path, $realpath = true)
    {
        $path = str_replace(['\\', "/"], "/", $path);
        if ($realpath) {
            $path = realpath($path);
            // realpath add backslashes again, but it also cannot work with mixed slashes
            $path = str_replace(['\\', "/"], "/", $path);
        }
        return $path;
    }

    /**
     * Get array of flat files
     * @param string $directory
     * @param string|null $regex The regex to search for
     * @param bool $recursive
     * @param bool $includeDirectories
     * @return string[]
     */
    public static function getFiles($directory, $regex = null, $recursive = false, $includeDirectories = false)
    {
        if (!is_dir($directory)) {
            return [];
        }
        $directory = self::sanitizePath($directory);
        $files = scandir($directory);
        $arr = [];
        foreach ($files as $file) {
            if ($file == "." || $file == "..") {
                continue;
            }
            $path = self::sanitizePath($directory . "/" . $file);
            if (is_dir($path)) {
                if ($recursive) {
                    $arr = array_merge($arr, self::getFiles($path, $regex, $recursive));
                }
                if ($includeDirectories) {
                    $arr[] = $path;
                }
                continue;
            }
            if ($regex) {
                if (!preg_match($regex, $file)) {
                    continue;
                }
            }
            $arr[] = $path;
        }
        return $arr;
    }

    /**
     * Delete a directory
     * @param string $directory
     * @param bool $recursive
     */
    public static function deleteDirectory($directory, $recursive = false)
    {
        if (!is_dir($directory)) {
            return;
        }
        $files = self::getFiles($directory, null, false, true);
        foreach ($files as $file) {
            if (is_dir($file)) {
                if ($recursive) {
                    self::deleteDirectory($file, $recursive);
                }
            } else {
                unlink($file);
            }
        }
        rmdir($directory);
    }
}
