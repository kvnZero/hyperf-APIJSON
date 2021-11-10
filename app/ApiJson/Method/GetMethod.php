<?php

namespace App\ApiJson\Method;

use App\ApiJson\Parse\Handle;

class GetMethod extends AbstractMethod
{
    protected function validateCondition(): bool
    {
        return $this->method == 'GET';
    }

    protected function process()
    {
        $handle = new Handle($this->tableEntity->getConditionEntity(), $this->tableEntity);
        $handle->build($this->query);

        $queryMany = $this->isQueryMany();
        if (!$queryMany) {
            $this->query->limit(1);
        }
        $result = $this->query->all();
        if ($queryMany) {
            foreach ($result as $key => $item) {
                $result[$key] = $this->arrayQuery ? [$this->tableEntity->getTableName() => $item] : $item;
            }
        } else {
            $result = current($result);
        }
        return $result ?: [];
    }
}