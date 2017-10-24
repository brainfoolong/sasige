<?php

namespace Nullix\Sasige;

/**
 * Class Console
 */
class Console
{
    /**
     * Execute console calls
     */
    public static function run()
    {
        $isTest = defined("SASIGE_TEST");
        try {
            $command = isset($_SERVER["argv"][1]) ? $_SERVER["argv"][1] : "";
            switch ($command) {
                case "watch":
                    Console::writeStdout("Started file watch\n");
                    Console::writeStdout("Close with CTRL+C\n\n");
                    $watch = new FileWatch();
                    $watch->setDirectories([
                        SASIGE_PROJECT_ROOT . "/pages",
                        SASIGE_PROJECT_ROOT . "/theme",
                        SASIGE_PROJECT_ROOT . "/sasige",
                        SASIGE_PROJECT_ROOT . "/static"
                    ]);
                    $watch->setFiles([
                        SASIGE_PROJECT_ROOT . "/config.php"
                    ]);
                    $watch->start();
                    break;
                case "build":
                    Generator::build();
                    break;
                case "serve":
                    $port = Config::getServerPort();
                    Console::writeStdout("Started debug webserver at port $port\n");
                    Console::writeStdout("Open http://localhost:$port\n");
                    Console::writeStdout("Close with CTRL+C\n\n");
                    if (!$isTest) {
                        exec("php -S 0.0.0.0:$port -t "
                            . escapeshellarg(File::concat(SASIGE_PROJECT_ROOT, Config::getOutputFolder())));
                    }

                    break;
                default:
                    $port = Config::getServerPort();
                    $info = [
                        "=====================================",
                        "Sasige - Nullix Static Site Generator v." . SASIGE_VERSION,
                        "Usage: sasige (build|watch|serve)",
                        "=====================================",
                        "build      Generate and build the project into the output folder. WARNING: All files in the '" . Config::getOutputFolder() . "' folder will be deleted by this command",
                        "watch      Start a filewatcher that rebuild the project everytime a file in the project has changed",
                        "serve      Start a debug webserver on port $port",
                        "=====================================",
                    ];
                    self::writeStdout(implode("\n", $info));
            }
        } catch (\Exception $e) {
            self::writeStderr("\n\nApplication ended with an error\n\n");
            self::writeException($e);
        }
    }

    /**
     * Write to stdout
     * @param string $text
     */
    public static function writeStdout($text)
    {
        self::write($text, STDOUT);
    }

    /**
     * Write to stderr
     * @param string $text
     */
    public static function writeStderr($text)
    {
        self::write($text, STDERR);
    }

    /**
     * Write to exception
     * @param \Exception $e
     */
    public static function writeException(\Exception $e)
    {
        self::writeStderr($e->getMessage() . "\n" . $e->getTraceAsString());
    }

    /**
     * Write to output
     * @param string $text
     * @param resource $flag
     */
    private static function write($text, $flag)
    {
        fwrite($flag, $text);
    }
}
