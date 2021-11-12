<?php

namespace App\ApiJson\Handle;

class FunctionOrderHandle extends AbstractHandle
{
    protected string $keyWord = '@order';

    public function buildModel()
    {
        if (!in_array($this->keyWord, array_keys($this->condition->getCondition()))) {
            return;
        }
        foreach (array_filter($this->condition->getCondition(), function($key){
            return $key == $this->keyWord;
        }, ARRAY_FILTER_USE_KEY) as $key => $value)
        {
            $orderArr = explode(',', $value);
            $orderCondition = [];
            foreach ($orderArr as $order) {
                $orderCondition[] = [str_replace(['-', '+'], '', $order), str_ends_with($order, '-') ? 'desc' : 'asc'];
            }
            $this->condition->setOrder($orderCondition);
            $this->unsetKey[] = $this->keyWord;
        }
    }
}