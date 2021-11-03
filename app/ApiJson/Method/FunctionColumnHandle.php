<?php

namespace App\ApiJson\Method;

class FunctionColumnHandle extends MethodHandleInterface
{
    protected function validateCondition(): bool
    {
        return $this->key === '@column';
    }

    protected function buildModel()
    {
        $this->value = str_replace([';',':'], [',', ' AS '], $this->value);
        $this->builder->select(explode(',', $this->value));
    }
}