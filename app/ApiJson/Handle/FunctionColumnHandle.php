<?php

namespace App\ApiJson\Handle;

class FunctionColumnHandle extends AbstractHandle
{
    protected string $keyWord = '@column';

    public function buildModel()
    {
        if (!in_array($this->keyWord, array_keys($this->condition->getCondition()))) {
            return;
        }
        foreach (array_filter($this->condition->getCondition(), function($key){
            return $key == $this->keyWord;
        }, ARRAY_FILTER_USE_KEY) as $key => $value)
        {
            $value = str_replace([';',':'], [',', ' AS '], $value);
            $this->query->select(explode(',', $value));
            $this->unsetKey[] = $this->keyWord;
        }
    }
}