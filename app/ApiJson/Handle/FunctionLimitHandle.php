<?php

namespace App\ApiJson\Handle;

class FunctionLimitHandle extends AbstractHandle
{
    protected function validateCondition(): bool
    {
        return $this->key === '@limit';
    }

    protected function buildModel()
    {
        $this->query->limit((int)$this->value);
    }
}