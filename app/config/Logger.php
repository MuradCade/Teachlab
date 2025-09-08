<?php

namespace App\Config;

class Logger
{
    protected string $logPath;

    public function __construct(string $filename = 'app.log')
    {
        $root = dirname(__DIR__, 2); // Go up two levels to reach project root
        $this->logPath = $root . '/logs/' . $filename;

        if (!file_exists(dirname($this->logPath))) {
            mkdir(dirname($this->logPath), 0777, true);
        }

        if (!file_exists($this->logPath)) {
            touch($this->logPath);
        }
    }

    public function log(string $message, string $level = 'INFO'): void
    {
        $date = date('Y-m-d H:i:s');
        $entry = "[$date] [$level] $message" . PHP_EOL;

        file_put_contents($this->logPath, $entry, FILE_APPEND);
    }

    public function error(string $message): void
    {
        $this->log($message, 'ERROR');
    }

    public function warning(string $message): void
    {
        $this->log($message, 'WARNING');
    }

    public function info(string $message): void
    {
        $this->log($message, 'INFO');
    }
}
