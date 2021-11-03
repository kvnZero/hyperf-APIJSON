<?php

namespace App\ApiJson\Method;

class WhereHandle extends MethodHandleInterface
{
    protected function validateCondition(): bool
    {
        return true;
    }

    protected function buildModel()
    {
        $this->builder->where($this->sanitizeKey, $this->value);
    }
}