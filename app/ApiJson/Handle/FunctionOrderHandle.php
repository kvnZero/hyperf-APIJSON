<?php

namespace App\ApiJson\Handle;

class FunctionOrderHandle extends AbstractHandle
{
    protected function validateCondition(): bool
    {
        return $this->key === '@order';
    }

    protected function buildModel()
    {
        $orderArr = explode(',', $this->value);
        foreach ($orderArr as $order) {
            $this->query->orderBy(str_replace(['-', '+'], '', $order), str_ends_with($order, '-') ? 'desc' : 'asc');
        }
    }
}