<?php

namespace App\ApiJson\Handle;

class FunctionOffsetHandle extends AbstractHandle
{
    protected function validateCondition(): bool
    {
        return $this->key === '@offset';
    }

    protected function buildModel()
    {
        $this->query->offset((int)$this->value);
    }
}