<?php

namespace App\ApiJson\Handle;

class FunctionLimitHandle extends AbstractHandle
{
    protected string $keyWord = '@limit';

    protected function buildModel()
    {
        if (!in_array($this->keyWord, array_keys($this->condition->getCondition()))) {
            return;
        }
        foreach (array_filter($this->condition->getCondition(), function($key){
            return $key == $this->keyWord;
        }, ARRAY_FILTER_USE_KEY) as $key => $value)
        {
            $this->condition->setLimit((int)$value);
            $this->unsetKey[] = $this->keyWord;
        }
    }
}