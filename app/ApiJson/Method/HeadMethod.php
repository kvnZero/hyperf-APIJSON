<?php

namespace App\ApiJson\Method;

use App\Constants\ResponseCode;

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
            'code' => ResponseCode::SUCCESS,
            'msg' => ResponseCode::getMessage(ResponseCode::SUCCESS),
            'count' => $this->query->count()
        ];
    }
}