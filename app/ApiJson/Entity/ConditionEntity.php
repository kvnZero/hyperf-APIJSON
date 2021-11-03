<?php

namespace App\ApiJson\Entity;

use App\ApiJson\Interface\QueryInterface;
use App\ApiJson\Handle\AbstractMethodHandle;
use App\ApiJson\Handle\FunctionColumnHandle;
use App\ApiJson\Handle\FunctionGroupHandle;
use App\ApiJson\Handle\FunctionHavingHandle;
use App\ApiJson\Handle\FunctionOrderHandle;
use App\ApiJson\Handle\WhereBetweenHandle;
use App\ApiJson\Handle\WhereExistsHandle;
use App\ApiJson\Handle\WhereHandle;
use App\ApiJson\Handle\WhereInHandle;
use App\ApiJson\Handle\WhereJsonContainsHandle;
use App\ApiJson\Handle\WhereLikeHandle;
use App\ApiJson\Handle\WhereRawHandle;
use App\ApiJson\Handle\WhereRegexpHandle;

class ConditionEntity
{
    /**
     * 匹配规则 根据从上自下优先先匹先出
     * @var AbstractMethodHandle[]
     */
    protected array $methodRules = [
        FunctionColumnHandle::class,
        FunctionHavingHandle::class,
        FunctionGroupHandle::class,
        FunctionOrderHandle::class,
        WhereJsonContainsHandle::class,
        WhereBetweenHandle::class,
        WhereExistsHandle::class,
        WhereRegexpHandle::class,
        WhereLikeHandle::class,
        WhereRawHandle::class,
        WhereInHandle::class,
        WhereHandle::class,
    ];

    /**
     * @param array $condition 条件
     */
    public function __construct(protected array $condition)
    {
    }

    /**
     * 整理语句
     */
    public function setQueryCondition(QueryInterface $query)
    {
        foreach ($this->condition as $key => $value) {
            foreach ($this->methodRules as $rule) {
                $methodRule = new $rule($query, $key, $value);
                if ($methodRule->handle()) break;
            }
        }
    }
}