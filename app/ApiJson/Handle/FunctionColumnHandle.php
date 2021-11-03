<?php

namespace App\ApiJson\Handle;

class FunctionColumnHandle extends AbstractMethodHandle
{
    protected function validateCondition(): bool
    {
        return $this->key === '@column';
    }

    protected function buildModel()
    {
        $this->value = str_replace([';',':'], [',', ' AS '], $this->value);
        $this->query->select(explode(',', $this->value));
    }
}