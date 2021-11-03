<?php

namespace App\ApiJson\Method;

class WhereRawHandle extends MethodHandleInterface
{
    protected function validateCondition(): bool
    {
        return str_ends_with($this->key, '{}') && !is_array($this->value);
    }

    protected function buildModel()
    {
        $conditionArr = explode(',', $this->value);
        $sql = [];
        foreach ($conditionArr as $condition) {
            $sql[] = sprintf("`%s`%s", $this->sanitizeKey, trim($condition));
        }
        $this->builder->whereRaw(join(' OR ', $sql)); //3.2.3
    }
}