<?php

namespace App\ApiJson\Handle;

class WhereRawHandle extends AbstractHandle
{
    protected function buildModel()
    {
        foreach (array_filter($this->condition->getCondition(), function($key){
            return str_ends_with($key, '{}');
        }, ARRAY_FILTER_USE_KEY) as $key => $value)
        {
            if (is_array($value)) continue;
            $conditionArr = explode(',', (string)$value);
            $sql = [];
            foreach ($conditionArr as $condition) {
                $sql[] = sprintf("`%s`%s", $this->sanitizeKey($key), trim($condition));
            }
            $this->condition->addQueryWhere($key, join(' OR ', $sql), []);
            $this->unsetKey[] = $key;
        }
    }
}