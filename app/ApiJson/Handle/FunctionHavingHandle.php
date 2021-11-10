<?php

namespace App\ApiJson\Handle;

class FunctionHavingHandle extends AbstractHandle
{
    protected string $keyWord = '@having';

    public function buildModel()
    {
        if (!in_array($this->keyWord, array_keys($this->condition->getCondition()))) {
            return;
        }
        foreach (array_filter($this->condition->getCondition(), function($key){
            return $key == $this->keyWord;
        }, ARRAY_FILTER_USE_KEY) as $key => $value)
        {
            $havingArr = explode(';', $value);
            foreach ($havingArr as $having) {
                $this->query->having($having);
            }
            $this->unsetKey[] = $this->keyWord;
        }
    }
}