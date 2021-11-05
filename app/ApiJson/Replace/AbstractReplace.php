<?php

namespace App\ApiJson\Replace;

abstract class AbstractReplace
{
    public function __construct(protected string $key, protected $value, protected $condition, protected array $extendData)
    {
    }

    public function handle(): ?array
    {
        if (!$this->validateCondition()) return null;
        return $this->process();
    }

    abstract protected function validateCondition(): bool;

    abstract protected function process();
}