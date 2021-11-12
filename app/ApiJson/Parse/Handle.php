<?php


declare(strict_types=1);
/**
 * @author   kvnZero
 * @contact  kvnZero@github.com
 * @time     2021/11/10 9:08 下午
 */

namespace App\ApiJson\Parse;

use App\ApiJson\Entity\ConditionEntity;
use App\ApiJson\Entity\TableEntity;
use App\ApiJson\Handle\AbstractHandle;
use App\ApiJson\Handle\FunctionColumnHandle;
use App\ApiJson\Handle\FunctionGroupHandle;
use App\ApiJson\Handle\FunctionHavingHandle;
use App\ApiJson\Handle\FunctionLimitHandle;
use App\ApiJson\Handle\FunctionOffsetHandle;
use App\ApiJson\Handle\FunctionOrderHandle;
use App\ApiJson\Handle\WhereBetweenHandle;
use App\ApiJson\Handle\WhereExistsHandle;
use App\ApiJson\Handle\WhereHandle;
use App\ApiJson\Handle\WhereInHandle;
use App\ApiJson\Handle\WhereJsonContainsHandle;
use App\ApiJson\Handle\WhereLikeHandle;
use App\ApiJson\Handle\WhereRawHandle;
use App\ApiJson\Handle\WhereRegexpHandle;
use App\ApiJson\Interface\QueryInterface;
use App\ApiJson\Replace\AbstractReplace;
use App\ApiJson\Replace\KeywordCountReplace;
use App\ApiJson\Replace\KeywordPageReplace;
use App\ApiJson\Replace\QuoteReplace;

class Handle
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

    public function __construct(protected ConditionEntity $conditionEntity, protected TableEntity $tableEntity)
    {
    }

    public function build()
    {
        foreach ($this->replaceRules as $replaceRuleClass) {
            /** @var AbstractReplace $replaceRule */
            $replaceRule = new $replaceRuleClass($this->conditionEntity);
            $replaceRule->handle();
        }
        foreach ($this->methodRules as $methodRuleClass) {
            /** @var AbstractHandle $methodRule */
            $methodRule = new $methodRuleClass($this->conditionEntity);
            $methodRule->handle();
        }
    }
}