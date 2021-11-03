<?php

namespace App\ApiJson\Method;

class WhereRegexpHandle extends MethodHandleInterface
{
    protected function validateCondition(): bool
    {
        return str_ends_with($this->key, '%');
    }

    protected function buildModel()
    {
        $value = !is_array($this->value) ? [$this->value] : $this->value;
        $sql = [];
        foreach ($value as $item) {
            $sql[] = sprintf("%s REGEXP %s", $this->sanitizeKey, trim($item));
        }
        $this->builder->whereRaw(join(' OR ', $sql)); //3.2.3
    }
}