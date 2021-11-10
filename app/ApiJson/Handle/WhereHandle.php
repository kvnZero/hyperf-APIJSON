<?php

namespace App\ApiJson\Handle;

class WhereHandle extends AbstractHandle
{
    protected function buildModel()
    {
        foreach ($this->condition->getCondition() as $key => $value)
        {
            $this->query->where($this->sanitizeKey($key), '=', $value);
            $this->unsetKey[] = $key;
        }
    }
}