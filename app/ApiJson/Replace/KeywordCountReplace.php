<?php

namespace App\ApiJson\Replace;

use App\ApiJson\Interface\QueryInterface;
use Hyperf\Utils\Arr;

class KeywordCountReplace extends AbstractReplace
{
    protected function validateCondition(): bool
    {
        return $this->key == 'count';
    }

    protected function process()
    {
        $this->key = "@limit";
        return [$this->key, $this->value]; //必须
    }
}