<?php

namespace App\ApiJson\Entity;

use App\ApiJson\Handle\FunctionLimitHandle;
use App\ApiJson\Handle\FunctionOffsetHandle;
use App\ApiJson\Interface\QueryInterface;
use App\ApiJson\Handle\AbstractHandle;
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
use App\ApiJson\Method\AbstractMethod;
use App\ApiJson\Replace\AbstractReplace;
use App\ApiJson\Replace\KeywordCountReplace;
use App\ApiJson\Replace\KeywordPageReplace;
use App\ApiJson\Replace\QuoteReplace;

class ConditionEntity
{
    /**
     * 替换规则
     * @var AbstractReplace[]
     */
    protected array $replaceRules = [
        KeywordCountReplace::class,
        KeywordPageReplace::class,
        QuoteReplace::class,
    ];


    /**
     * 匹配规则 根据从上自下优先先匹先出
     * @var AbstractHandle[]
     */
    protected array $methodRules = [
        FunctionColumnHandle::class,
        FunctionHavingHandle::class,
        FunctionOffsetHandle::class,
        FunctionLimitHandle::class,
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
    public function __construct(protected array $condition, protected array $extendData = [])
    {
    }

    protected function replaceHandle($key, $value, array $condition = []): array
    {
        foreach ($this->replaceRules as $rule) {
            /** @var AbstractReplace $replaceRule */
            $replaceRule = new $rule($key, $value, $condition, $this->extendData);
            $response = $replaceRule->handle();
            if (!is_null($response)) return $response;
        }
        return [$key, $value];
    }

    /**
     * 整理语句
     */
    public function setQueryCondition(QueryInterface $query)
    {
        foreach ($this->condition as $key => $value) {
            [$key, $value] = $this->replaceHandle($key, $value, $this->condition); //解决引用问题
            /** @var AbstractMethod $rule */
            foreach ($this->methodRules as $rule) {
                $methodRule = new $rule($query, $key, $value);
                if ($methodRule->handle()) break;
            }
        }
    }
}