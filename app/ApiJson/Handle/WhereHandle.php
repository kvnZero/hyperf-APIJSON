<?php

namespace App\ApiJson\Handle;

class WhereHandle extends AbstractMethodHandle
{
    protected function validateCondition(): bool
    {
        return true;
    }

    protected function buildModel()
    {
        $this->query->where($this->sanitizeKey, $this->value);
    }
}