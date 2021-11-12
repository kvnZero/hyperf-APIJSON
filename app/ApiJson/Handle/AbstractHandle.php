<?php

namespace App\ApiJson\Handle;

use App\ApiJson\Entity\ConditionEntity;
use App\ApiJson\Interface\QueryInterface;

abstract class AbstractHandle
{
    /** @var string 关键字 */
    protected string $keyWord;

    protected array $unsetKey = [];

    public function __construct(protected ConditionEntity $condition)
    {
    }

    protected function sanitizeKey(string $key): string
    {
        preg_match('#(?<key>[a-zA-z0-9_]+)#', $key, $match);
        return $match['key'] ?? $key;
    }

    public function handle()
    {
        $this->buildModel();
        $this->unsetKeySaveCondition();
    }

    protected function unsetKeySaveCondition()
    {
        if (empty($this->unsetKey)) return;
        $condition = $this->condition->getCondition();
        foreach ($this->unsetKey as $key) {
            unset($condition[$key]);
        }
        $this->condition->setCondition($condition);
    }

    abstract protected function buildModel();
}