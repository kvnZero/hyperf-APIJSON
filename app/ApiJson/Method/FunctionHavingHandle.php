<?php

namespace App\ApiJson\Method;

class FunctionHavingHandle extends MethodHandleInterface
{
    protected function validateCondition(): bool
    {
        return $this->key === '@having';
    }

    protected function buildModel()
    {
        $havingArr = explode(';', $this->value);
        foreach ($havingArr as $having) {
            $this->builder->havingRaw($having);
        }
    }
}