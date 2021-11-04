<?php

namespace App\ApiJson\Handle;

class WhereBetweenHandle extends AbstractHandle
{
    protected function validateCondition(): bool
    {
        return str_ends_with($this->key, '$');
    }

    protected function buildModel()
    {
        $value = !is_array($this->value) ? [$this->value] : $this->value;
        $sql = [];
        foreach ($value as $item) {
            $itemArr = explode(',', $item);
            $sql[] = sprintf("%s BETWEEN %s AND %s", $this->sanitizeKey, trim($itemArr[0]), trim($itemArr[1]));
        }
        $this->query->whereRaw(join(' OR ', $sql)); //3.2.3
    }
}