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
use App\ApiJson\Handle\FunctionCombineHandle;
use App\ApiJson\Handle\FunctionGroupHandle;
use App\ApiJson\Handle\FunctionHavingHandle;
use App\ApiJson\Handle\FunctionLimitHandle;
use App\ApiJson\Handle\FunctionOffsetHandle;
use App\ApiJson\Handle\FunctionOrderHandle;
use App\ApiJson\Handle\FunctionProcedureHandle;
use App\ApiJson\Handle\WhereBetweenHandle;
use App\ApiJson\Handle\WhereExistsHandle;
use App\ApiJson\Handle\WhereHandle;
use App\ApiJson\Handle\WhereInHandle;
use App\ApiJson\Handle\WhereJsonContainsHandle;
use App\ApiJson\Handle\WhereLikeHandle;
use App\ApiJson\Handle\WhereNotInHandle;
use App\ApiJson\Handle\WhereOpHandle;
use App\ApiJson\Handle\WhereRawHandle;
use App\ApiJson\Handle\WhereRegexpHandle;
use App\ApiJson\Handle\WhereSubQueryHandle;
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
        'query' => [
            KeywordCountReplace::class,
            KeywordPageReplace::class,
            QuoteReplace::class,
        ],
        'update' => []
    ];


    /**
     * 匹配规则 根据从上自下优先先匹先出
     * @var AbstractHandle[]
     */
    protected array $queryMethodRules = [
        'query' => [
            FunctionProcedureHandle::class,
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
            WhereNotInHandle::class,
            WhereLikeHandle::class,
            WhereRawHandle::class,
            WhereInHandle::class,
            WhereSubQueryHandle::class,
            WhereOpHandle::class,
            WhereHandle::class,
            FunctionCombineHandle::class
        ],
        'update' => [

        ]
    ];

    public function __construct(protected ConditionEntity $conditionEntity, protected TableEntity $tableEntity)
    {
    }

    public function buildQuery()
    {
        $this->build('query');
    }

    public function buildUpdate()
    {
        $this->build('update');
    }

    protected function build(string $action)
    {
        foreach ($this->replaceRules[$action] ?? [] as $replaceRuleClass) {
            /** @var AbstractReplace $replaceRule */
            $replaceRule = new $replaceRuleClass($this->conditionEntity);
            $replaceRule->handle();
        }
        foreach ($this->queryMethodRules[$action] ?? [] as $methodRuleClass) {
            /** @var AbstractHandle $methodRule */
            $methodRule = new $methodRuleClass($this->conditionEntity);
            $methodRule->handle();
        }
    }
}