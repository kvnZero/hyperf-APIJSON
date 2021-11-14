<?php

namespace App\ApiJson\Handle;

class WhereOpHandle extends AbstractHandle
{
    protected function buildModel()
    {
        foreach ($this->condition->getCondition() as $key => $value)
        {
            if(str_ends_with($key, '>=')) {
                $op = '>=';
            } else if(str_ends_with($key, '<=')) {
                $op = '<=';
            } else if(str_ends_with($key, '>')) {
                $op = '>';
            } else if(str_ends_with($key, '<')) {
                $op = '<';
            }
            if (!isset($op)) continue;

            $sql = sprintf("`%s` %s ?", $this->sanitizeKey($key), $op);
            $this->condition->addQueryWhere($key, $sql, [$value]);
            $this->unsetKey[] = $key;
        }
    }
}