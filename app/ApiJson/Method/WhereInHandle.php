<?php

namespace App\ApiJson\Method;

class WhereInHandle extends MethodHandleInterface
{
    protected function validateCondition(): bool
    {
        return str_ends_with($this->key, '{}') && is_array($this->validateCondition());
    }

    protected function buildModel()
    {
        $this->builder->whereIn($this->sanitizeKey, $this->value);
    }
}