<?php

namespace App\ApiJson\Handle;

class WhereInHandle extends AbstractHandle
{
    protected function buildModel()
    {
        foreach (array_filter($this->condition->getCondition(), function($key){
            return str_ends_with($key, '{}');
        }, ARRAY_FILTER_USE_KEY) as $key => $value)
        {
            if (!is_array($value)) continue;
            $this->query->whereIn($this->sanitizeKey($key), $value);
            $this->unsetKey[] = $key;
        }
    }
}