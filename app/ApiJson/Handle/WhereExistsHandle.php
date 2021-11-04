<?php

namespace App\ApiJson\Handle;

use Hyperf\Database\Query\Builder;

class WhereExistsHandle extends AbstractHandle
{
    protected function validateCondition(): bool
    {
        return str_ends_with($this->key, '}{@');
    }

    protected function buildModel()
    {
        $this->query->whereExists(function(Builder $query) {
            $query = $query->from($this->value['from']);

            //这里应该再接入处理列表
            foreach ($this->value[$this->value['from']] as $k => $v) {
                $query->where($k, $v);
            }
        });
    }
}