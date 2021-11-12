<?php

namespace App\ApiJson\Handle;

class WhereSubQueryHandle extends AbstractHandle
{
    protected function buildModel()
    {
        foreach (array_filter($this->condition->getCondition(), function($key){
            return str_ends_with($key, '@');
        }, ARRAY_FILTER_USE_KEY) as $key => $value)
        {
            $query = $this->subTableQuery($value);

            $op = '=';
            if(str_ends_with($key, '>=@')) {
                $op = '>=';
            } else if(str_ends_with($key, '<=@')) {
                $op = '<=';
            } else if(str_ends_with($key, '>@')) {
                $op = '>';
            } else if(str_ends_with($key, '<@')) {
                $op = '<';
            }
            $sql = sprintf('`%s`%s(%s)', $this->sanitizeKey($key), $op, $query->toSql());
            $this->condition->addQueryWhere($key, $sql, $query->getBindings());
            $this->unsetKey[] = $key;
        }
    }
}