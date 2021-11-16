<?php

namespace App\ApiJson\Replace;

class QuoteReplace extends AbstractReplace
{
    protected function process()
    {
        $condition = $this->condition->getCondition();

        foreach (array_filter($condition, function($key){
            return str_ends_with($key, '@') && !str_ends_with($key, '}{@') && !str_ends_with($key, '{}@');
        }, ARRAY_FILTER_USE_KEY) as $key => $value)
        {
            if (!is_string($value)) continue;
            $path = str_replace(['/', '[]'], ['.', 'currentItem'], $value);
            if (str_starts_with($path, '.')) {
                $path = 'currentItem' . $path;
            }
            $newKey = substr($key, 0, strlen($key) - 1);
            $condition[$newKey] = data_get($this->condition->getExtendData(), $path);
            unset($condition[$key]);
            $this->condition->setCondition($condition);
        }
    }
}