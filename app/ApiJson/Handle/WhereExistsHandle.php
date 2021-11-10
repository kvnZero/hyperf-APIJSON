<?php

namespace App\ApiJson\Handle;

use Hyperf\Database\Query\Builder;

class WhereExistsHandle extends AbstractHandle
{
    protected function buildModel()
    {
        foreach (array_filter($this->condition->getCondition(), function($key){
            return str_ends_with($key, '}{@');
        }, ARRAY_FILTER_USE_KEY) as $key => $value)
        {
            $this->query->whereExists(function(Builder $query) use($value) {
                $query = $query->from($value['from']);
                //这里应该再接入处理列表
                foreach ($value[$value['from']] as $k => $v) {
                    $query->where($k, $v);
                }
            });
            $this->unsetKey[] = $key;
        }
    }
}