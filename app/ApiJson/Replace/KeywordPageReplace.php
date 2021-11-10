<?php

namespace App\ApiJson\Replace;

class KeywordPageReplace extends AbstractReplace
{
    protected function process()
    {
        $condition = $this->condition->getCondition();
        if (isset($condition['page'])) {
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

            $condition['@offset'] = ((int)$condition['page'] - 1) * $count;
            unset($condition['page']);
            $this->condition->setCondition($condition);
        }
    }
}