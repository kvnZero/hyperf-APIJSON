<?php

namespace App\ApiJson\Handle;

class WhereBetweenHandle extends AbstractHandle
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
                $itemArr = explode(',', $item);
                $sql[] = sprintf("%s BETWEEN %s AND %s", $this->sanitizeKey($key), trim($itemArr[0]), trim($itemArr[1]));
            }
            $this->query->whereRaw(join(' OR ', $sql)); //3.2.3
            $this->unsetKey[] = $key;
        }
    }
}