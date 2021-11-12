<?php

namespace App\ApiJson\Method;

use App\ApiJson\Parse\Handle;

class HeadMethod extends AbstractMethod
{
    protected function validateCondition(): bool
    {
        return $this->method == 'HEAD';
    }

    protected function process()
    {
        $handle = new Handle($this->tableEntity->getConditionEntity(), $this->tableEntity);
        $handle->build();
        return [
            'count' => $this->query->count()
        ];
    }
}