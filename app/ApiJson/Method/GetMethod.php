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

        $queryMany = $this->isQueryMany();
        if (!$queryMany) {
            $this->query->limit(1);
        }
        $result = $this->query->all();
        if ($queryMany) {
            foreach ($result as $key => $item) {
                $result[$key] = [$this->tableEntity->getTableName() => $item];
            }
        } else {
            $result = current($result);
        }
        return $result ?: [];
    }
}