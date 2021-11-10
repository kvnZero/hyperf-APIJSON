<?php

namespace App\ApiJson\Entity;

class ConditionEntity
{
    protected array $changeLog = [];

    /**
     * @param array $condition 条件
     */
    public function __construct(protected array $condition, protected array $extendData = [])
    {
    }

    public function getExtendData(): array
    {
        return $this->extendData;
    }

    public function setCondition(array $condition)
    {
        $this->log($condition);
        $this->condition = $condition;
    }

    public function getCondition(): array
    {
        return $this->condition;
    }

    protected function log(array $condition)
    {
        $this->changeLog[] = [
            'old' => $this->condition,
            'new' => $condition
        ];
    }
}