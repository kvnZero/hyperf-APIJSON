<?php

namespace App\ApiJson\Handle;

class WhereInHandle extends AbstractMethodHandle
{
    protected function validateCondition(): bool
    {
        return str_ends_with($this->key, '{}') && is_array($this->validateCondition());
    }

    protected function buildModel()
    {
        $this->query->whereIn($this->sanitizeKey, $this->value);
    }
}