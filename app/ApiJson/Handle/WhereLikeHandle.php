<?php

namespace App\ApiJson\Handle;

class WhereLikeHandle extends AbstractHandle
{
    protected function buildModel()
    {
        foreach (array_filter($this->condition->getCondition(), function($key){
            return str_ends_with($key, '$');
        }, ARRAY_FILTER_USE_KEY) as $key => $value)
        {
            $value = !is_array($value) ? [$value] : $value;
            $sql = [];
            foreach ($value as $item) {
                $sql[] = sprintf("%s LIKE %s", $this->sanitizeKey($key), trim($item));
            }
            $this->query->whereRaw(join(' OR ', $sql));
            $this->unsetKey[] = $key;
        }
    }
}