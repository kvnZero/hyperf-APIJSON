<?php

namespace App\ApiJson\Handle;

class FunctionGroupHandle extends AbstractMethodHandle
{
    protected function validateCondition(): bool
    {
        return $this->key === '@group';
    }

    protected function buildModel()
    {
        $groupArr = explode(',', $this->value);
        $this->query->groupBy($groupArr);
    }
}