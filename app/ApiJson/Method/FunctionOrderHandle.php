<?php

namespace App\ApiJson\Method;

class FunctionOrderHandle extends MethodHandleInterface
{
    protected function validateCondition(): bool
    {
        return $this->key === '@order';
    }

    protected function buildModel()
    {
        $orderArr = explode(',', $this->value);
        foreach ($orderArr as $order) {
            $this->builder->orderBy(str_replace(['-', '+'], '', $order), str_ends_with($order, '-') ? 'desc' : 'asc');
        }
    }
}