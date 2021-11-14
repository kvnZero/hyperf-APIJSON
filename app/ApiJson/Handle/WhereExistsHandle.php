<?php

namespace App\ApiJson\Handle;

class WhereExistsHandle extends AbstractHandle
{
    protected function buildModel()
    {
        foreach (array_filter($this->condition->getCondition(), function($key){
            return str_ends_with($key, '}{@');
        }, ARRAY_FILTER_USE_KEY) as $key => $value)
        {
            $query = $this->subTableQuery($value);
            $sql = sprintf('EXISTS(%s)', $query->toSql());
            $this->condition->addQueryWhere($key, $sql, $query->getBindings());
            $this->unsetKey[] = $key;
        }
    }
}