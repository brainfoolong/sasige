<?php

namespace Nullix\Sasige;

/**
 * Class Url
 */
class Url
{
    /**
     * Concat url parts with a slash
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
}
