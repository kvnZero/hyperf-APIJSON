<?php

namespace App\ApiJson\Handle;

class FunctionHavingHandle extends AbstractHandle
{
    protected function validateCondition(): bool
    {
        return $this->key === '@having';
    }

    protected function buildModel()
    {
        $havingArr = explode(';', $this->value);
        foreach ($havingArr as $having) {
            $this->query->having($having);
        }
    }
}