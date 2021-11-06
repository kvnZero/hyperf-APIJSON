<?php

namespace App\ApiJson\Replace;

class QuoteReplace extends AbstractReplace
{
    protected function validateCondition(): bool
    {
        return str_ends_with($this->key, '@') && !str_ends_with($this->key, '}{@');
    }

    protected function process()
    {
        $path = str_replace(['/', '[]'], ['.', 'currentItem'], $this->value);

        $this->value = data_get($this->extendData, $path);
        $this->key = substr($this->key, 0, strlen($this->key) - 1);

        return [$this->key, $this->value]; //必须
    }
}