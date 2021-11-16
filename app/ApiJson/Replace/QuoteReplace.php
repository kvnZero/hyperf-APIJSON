<?php

namespace App\ApiJson\Replace;

class QuoteReplace extends AbstractReplace
{
    protected function process()
    {
        $condition = $this->condition->getCondition();

        foreach (array_filter($condition, function($key){
            return str_ends_with($key, '@');
        }, ARRAY_FILTER_USE_KEY) as $key => $value)
        {
            if (!is_string($value)) continue;
            $path = str_replace(['/', '[]'], ['.', 'currentItem'], $value);
            if (str_starts_with($path, '.')) {
                $path = 'currentItem' . $path;
            }
            $value = data_get($this->condition->getExtendData(), $path);
            if (!is_null($value)) { //常规情况下的引用
                $newKey = substr($key, 0, strlen($key) - 1);
                $condition[$newKey] = $value;
                unset($condition[$key]);
            } else { //非常规情况下引入 比如引入子查询等
                $path .= '@';
                $value = data_get($this->condition->getExtendData(), $path);
                $condition[$key] = $value;
            }
            $this->condition->setCondition($condition);
        }
    }
}