<?php

namespace App\ApiJson\Handle;

class FunctionCombineHandle extends AbstractHandle
{
    protected string $keyWord = '@combine';

    public function buildModel()
    {
        if (!in_array($this->keyWord, array_keys($this->condition->getCondition()))) {
            return;
        }
        foreach (array_filter($this->condition->getCondition(), function($key){
            return $key == $this->keyWord;
        }, ARRAY_FILTER_USE_KEY) as $key => $value)
        {
            $conditionKeyArr = explode(',', $value);
            $op = [
                '&' => [],
                '|' => [],
                '!' => []
            ];
            foreach ($conditionKeyArr as $conditionKey) {
                if (str_starts_with($conditionKey, '&')) {
                    $op['&'] = $conditionKey;
                } else if (str_starts_with($conditionKey, '!')) {
                    $op['!'] = $conditionKey;
                } else {
                    $op['|'] = $conditionKey;
                }
            }
            $sql = [];
            $bind = [];
            $queryWhere = $this->condition->getQueryWhere();
            foreach ($op as $opKey => $opValue) {
                if (empty($value)) continue;
                $subSql = [];
                foreach ($opValue as $key) {
                    $subSql[] = $queryWhere[$key]['sql'];
                    $bind = array_merge($bind, $queryWhere[$key]['bind']);
                    unset($queryWhere[$key]);
                }
                $boolean = ' OR ';
                if ($opKey == '&') $boolean = ' AND ';
                $pref = ($opKey == '!') ? '!' : '';
                $sql[] = sprintf('%s(%s)', $pref, join($boolean, $subSql));
            }
            $queryWhere[$this->keyWord] = [
                'sql' => join(' AND ', $sql),
                'bind' => $bind
            ];
        }
    }
}