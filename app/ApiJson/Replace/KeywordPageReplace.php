<?php

namespace App\ApiJson\Replace;

class KeywordPageReplace extends AbstractReplace
{
    protected function process()
    {
        $condition = $this->condition->getCondition();
        if (isset($condition['page'])) {
            $count = 10;
            if (!empty($condition['count'])) {
                $count = (int) $condition['count'];
            } else if (!empty($condition['limit'])) {
                $count = (int) $condition['limit'];
            } else if (!empty($condition['@limit'])) {
                $count = (int) $condition['@limit'];
            } else if (!empty($condition['@count'])) {
                $count = (int) $condition['@count'];
            }

            $condition['@offset'] = ((int)$condition['page'] - 1) * $count;
            unset($condition['page']);
            $this->condition->setCondition($condition);
        }
    }
}