<?php

namespace App\ApiJson\Replace;

class QuoteReplace extends AbstractReplace
{
    protected function process()
    {
        $condition = $this->condition->getCondition();

        foreach (array_filter($condition, function($key){
            return str_ends_with($key, '@') && !str_ends_with($key, '}{@');
        }, ARRAY_FILTER_USE_KEY) as $key => $value)
        {
            $path = str_replace(['/', '[]'], ['.', 'currentItem'], $value);
            $newKey = substr($key, 0, strlen($key) - 1);
            $condition[$newKey] = data_get($this->condition->getExtendData(), $path);
            unset($condition[$key]);
            $this->condition->setCondition($condition);
        }
    }
}