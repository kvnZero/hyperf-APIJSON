<?php

namespace App\ApiJson\Handle;

class WhereJsonContainsHandle extends AbstractHandle
{
    protected function validateCondition(): bool
    {
        return str_ends_with($this->key, '<>');
    }

    protected function buildModel()
    {
        $value = !is_array($this->value) ? [$this->value] : $this->value;
        $sql = [];
        foreach ($value as $item) {
            $sql[] = sprintf("json_contains(%s,%s)", $this->sanitizeKey, trim($item));
        }
        $this->query->whereRaw(join(' OR ', $sql)); //3.2.3
    }
}