<?php

namespace App\ApiJson\Handle;

class FunctionOffsetHandle extends AbstractHandle
{
    protected string $keyWord = '@offset';

    protected function buildModel()
    {
        if (!in_array($this->keyWord, array_keys($this->condition->getCondition()))) {
            return;
        }
        foreach (array_filter($this->condition->getCondition(), function($key){
            return $key == $this->keyWord;
        }, ARRAY_FILTER_USE_KEY) as $key => $value)
        {
            $this->query->offset((int)$value);
            $this->unsetKey[] = $this->keyWord;
        }
    }
}