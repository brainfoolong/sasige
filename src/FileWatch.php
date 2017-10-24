<?php

namespace Nullix\Sasige;

/**
 * Class FileWatch
 */
class FileWatch
{
    /**
     * Is active
     * @var bool
     */
    private $active;

    /**
     * Rebuild count
     * @var int
     */
    private $count = 0;

    /**
     * The internal status for each file
     * @var array
     */
    private $status;

    /**
     * The directories for the watch
     * @var string[]
     */
    private $directories;

    /**
     * The files for the watch
     * @var string[]
     */
    private $files;

    /**
     * Start the file watch
     */
    public function start()
    {
        $isTest = defined("SASIGE_TEST");
        $this->active = true;
        while ($this->active) {
            try {
                $statusArray = [];
                if ($this->directories) {
                    foreach ($this->directories as $directory) {
                        $files = File::getFiles($directory, "~^[^\.]~", true, false);
                        foreach ($files as $file) {
                            $time = filemtime($file);
                            $statusArray[$file] = [
                                "time" => $time
                            ];
                        }
                    }
                }
                if ($this->files) {
                    foreach ($this->files as $file) {
                        $time = filemtime($file);
                        $statusArray[$file] = [
                            "time" => $time
                        ];
                    }
                }
                if ($statusArray !== $this->status && $this->status !== null) {
                    $this->count++;
                    Console::writeStdout("Rebuild Nr." . sprintf("%05d", $this->count)
                        . " @" . date("Y-m-d H:i:s") . "\n");
                    $output = $result = null;
                    exec(escapeshellcmd(SASIGE_BIN_ROOT . "/sasige build"), $output, $result);
                    if ($result != 0) {
                        Console::writeStderr(implode("\n", $output) . "\n\n");
                    }
                }
                $this->status = $statusArray;
                usleep(1000 * 500);
                // throw error in testmode
                if ($isTest) {
                    throw new Exception("Testmode exception");
                }
            } catch (Exception $e) {
                Console::writeStdout("Filewatch loop error " . $e->getMessage() . " - Restart loop\n");
                if ($isTest) {
                    $this->active = false;
                }
            }
        }
    }

    /**
     * @param string[] $files
     */
    public function setFiles(array $files)
    {
        $this->files = $files;
    }

    /**
     * @param string[] $directories
     */
    public function setDirectories(array $directories)
    {
        $this->directories = $directories;
    }
}
