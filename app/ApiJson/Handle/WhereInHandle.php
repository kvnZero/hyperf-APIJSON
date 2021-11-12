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
            $sql = sprintf('`%s` in (?)', $this->sanitizeKey($key));
            $this->condition->addQueryWhere($key, $sql, [join(',', $value)]);
            $this->unsetKey[] = $key;
        }
    }
}