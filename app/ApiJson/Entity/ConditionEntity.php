<?php

namespace App\ApiJson\Entity;

class ConditionEntity
{
    protected array $methodRules = [

    ];

    /**
     * @param array $condition 条件
     */
    public function __construct(protected array $condition)
    {
        $this->formatSql();
    }

    /**
     * 整理语句
     */
    protected function formatSql()
    {

    }
}