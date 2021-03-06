<?php

namespace App\ApiJson\Handle;

class FunctionProcedureHandle extends AbstractHandle
{
    protected string $keyWord = '@procedure()';

    public function buildModel()
    {
        if (!in_array($this->keyWord, array_keys($this->condition->getCondition()))) {
            return;
        }

        foreach (array_filter($this->condition->getCondition(), function($key){
            return $key == $this->keyWord;
        }, ARRAY_FILTER_USE_KEY) as $key => $value)
        {
            $this->condition->setProcedure($value);
            $this->unsetKey[] = $this->keyWord;
        }
    }
}