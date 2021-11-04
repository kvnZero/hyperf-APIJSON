<?php

namespace App\ApiJson\Handle;

use App\ApiJson\Interface\QueryInterface;

abstract class AbstractHandle
{
    /** @var string 清洗后的查询key */
    protected string $sanitizeKey;

    public function __construct(protected QueryInterface $query, protected string $key, protected $value)
    {
        preg_match('#(?<key>[a-zA-z0-9_]+)#', $this->key, $match);
        $this->sanitizeKey = $match['key'] ?? $this->key;
    }

    public function handle(): bool
    {
        if (!$this->validateCondition()) return false;
        $this->buildModel();
        return true;
    }

    abstract protected function validateCondition(): bool;

    abstract protected function buildModel();
}