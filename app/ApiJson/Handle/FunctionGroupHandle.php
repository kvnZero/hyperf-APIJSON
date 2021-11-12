<?php

namespace App\ApiJson\Handle;

class FunctionGroupHandle extends AbstractHandle
{
    protected string $keyWord = '@group';

    public function buildModel()
    {
        if (!in_array($this->keyWord, array_keys($this->condition->getCondition()))) {
            return;
        }
        foreach (array_filter($this->condition->getCondition(), function($key){
            return $key == $this->keyWord;
        }, ARRAY_FILTER_USE_KEY) as $key => $value)
        {
            $groupArr = explode(',', $value);
            $this->condition->setGroup($groupArr);
            $this->unsetKey[] = $this->keyWord;
        }
    }
}