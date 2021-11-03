<?php

namespace App\ApiJson\Method;

use Hyperf\Database\Query\Builder;

abstract class MethodHandleInterface
{
    /** @var string 清洗后的查询key */
    protected string $sanitizeKey;

    public function __construct(protected Builder $builder, protected string $key, protected $value)
    {
        preg_match('#(?<key>[a-zA-z0-9_]+)#', $this->key, $match);
        $this->sanitizeKey = $match['key'] ?? $this->key;
    }

    public function handle(): string
    {
        if (!$this->validateCondition()) return "";
        return $this->buildModel();
    }

    abstract protected function validateCondition(): bool;

    abstract protected function buildModel();
}