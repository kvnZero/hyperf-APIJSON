<?php

namespace App\ApiJson\Handle;

class WhereHandle extends AbstractHandle
{
    protected function buildModel()
    {
        foreach ($this->condition->getCondition() as $key => $value)
        {
            $sql = sprintf("`%s` = ?", $this->sanitizeKey($key));
            $this->condition->addQueryWhere($key, $sql, [$value]);
            $this->unsetKey[] = $key;
        }
    }
}