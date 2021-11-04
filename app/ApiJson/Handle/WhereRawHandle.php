<?php

namespace App\ApiJson\Handle;

class WhereRawHandle extends AbstractHandle
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
        $this->query->whereRaw(join(' OR ', $sql)); //3.2.3
    }
}