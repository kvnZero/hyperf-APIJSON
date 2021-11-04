<?php

namespace App\ApiJson\Method;

use App\ApiJson\Interface\QueryInterface;

class GetMethod extends AbstractMethod
{
    protected function validateCondition(): bool
    {
        return $this->method == 'GET';
    }

    protected function process()
    {
        $conditionEntity = $this->tableEntity->getConditionEntity();
        $conditionEntity->setQueryCondition($this->query);

        $queryMany = str_ends_with($this->tableEntity->getTableName(), '[]');
        if (!$queryMany) {
            $this->query->limit(1);
        }
        $result = $this->query->all();
        !$queryMany && $result = current($result);

        return $result ?: [];
    }
}