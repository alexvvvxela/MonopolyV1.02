<?php

namespace Alexv\Monopoly;

class GameState
{
    private string $message = '';
    private array $logs = [];
    private int $logLimit = 20;

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function getLogs(): array
    {
        return $this->logs;
    }

    public function addLog(string $log): void
    {
        if (count($this->logs) >= $this->logLimit) {
            array_shift($this->logs);
        }
        $this->logs[] = $log;
    }

    public function clearLogs(): void
    {
        $this->logs = [];
    }
}
