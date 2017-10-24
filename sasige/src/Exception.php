<?php

namespace Nullix\Sasige;

/**
 * Class Exception
 */
class Exception extends \Exception
{
    /**
     * Error handler
     * @param int $errno
     * @param string $errstr
     * @param string $errfile
     * @param int $errline
     * @throws Exception
     */
    public static function errorHandler($errno, $errstr, $errfile, $errline)
    {
        $msg = $errstr . ' in ' . $errfile . ', line ' . $errline;
        throw new Exception($msg);
    }
}
