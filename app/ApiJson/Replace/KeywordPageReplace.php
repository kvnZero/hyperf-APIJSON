<?php

namespace App\ApiJson\Replace;

use App\ApiJson\Interface\QueryInterface;
use Hyperf\Utils\Arr;

class KeywordPageReplace extends AbstractReplace
{
    protected function validateCondition(): bool
    {
        return $this->key == 'page';
    }

    protected function process()
    {
        $count = 10;
        if (!empty($this->condition['count'])) {
            $count = (int) $this->condition['count'];
        } else if (!empty($this->condition['limit'])) {
            $count = (int) $this->condition['limit'];
        } else if (!empty($this->condition['@limit'])) {
            $count = (int) $this->condition['@limit'];
        } else if (!empty($this->condition['@count'])) {
            $count = (int) $this->condition['@count'];
        }

        $this->key = '@offset';
        $this->value = ((int)$this->value - 1) * $count;
        return [$this->key, $this->value]; //必须
    }
}