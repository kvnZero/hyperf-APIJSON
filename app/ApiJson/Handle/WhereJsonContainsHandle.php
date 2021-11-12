<?php

namespace App\ApiJson\Handle;

class WhereJsonContainsHandle extends AbstractHandle
{
    protected function buildModel()
    {
        foreach (array_filter($this->condition->getCondition(), function($key){
            return str_ends_with($key, '<>');
        }, ARRAY_FILTER_USE_KEY) as $key => $value)
        {
            $value = !is_array($value) ? [$value] : $value;
            $sql = [];
            $bind = [];
            foreach ($value as $item) {
                $sql[] = sprintf("json_contains(`%s`, ?)", $this->sanitizeKey($key));
                $bind = array_merge($bind, [trim($item)]);
            }
            $this->condition->addQueryWhere($key, join(' OR ', $sql), $bind);
            $this->unsetKey[] = $key;
        }
    }
}