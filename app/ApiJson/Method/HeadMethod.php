<?php

namespace App\ApiJson\Method;

class HeadMethod extends AbstractMethod
{
    protected function validateCondition(): bool
    {
        return $this->method == 'HEAD';
    }

    protected function process()
    {
        $conditionEntity = $this->tableEntity->getConditionEntity();
        $conditionEntity->setQueryCondition($this->query);
        return [
            'count' => $this->query->count()
        ];
    }
}