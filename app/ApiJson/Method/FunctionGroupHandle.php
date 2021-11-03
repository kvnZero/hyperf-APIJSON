<?php

namespace App\ApiJson\Method;

class FunctionGroupHandle extends MethodHandleInterface
{
    protected function validateCondition(): bool
    {
        return $this->key === '@group';
    }

    protected function buildModel()
    {
        $groupArr = explode(',', $this->value);
        $this->builder->groupBy($groupArr);
    }
}