<?php

namespace App\ApiJson\Replace;

class KeywordCountReplace extends AbstractReplace
{
    protected function process()
    {
        $condition = $this->condition->getCondition();
        if (isset($condition['count'])) {
            $condition['@limit'] = $condition['count'];
            unset($condition['count']);
            $this->condition->setCondition($condition);
        }
    }
}