<?php

namespace App\ApiJson\Handle;

use App\ApiJson\Entity\ConditionEntity;
use App\ApiJson\Entity\TableEntity;
use App\ApiJson\Interface\QueryInterface;
use App\Event\ApiJson\QueryHandleAfter;
use App\Event\ApiJson\QueryHandleBefore;
use Hyperf\Utils\ApplicationContext;
use Psr\EventDispatcher\EventDispatcherInterface;
use App\ApiJson\Parse\Handle;
use Hyperf\Contract\ConfigInterface;

abstract class AbstractHandle
{
    /** @var string 关键字 */
    protected string $keyWord;

    protected array $unsetKey = [];

    public function __construct(protected ConditionEntity $condition)
    {
    }

    protected function sanitizeKey(string $key): string
    {
        preg_match('#(?<key>[a-zA-z0-9_]+)#', $key, $match);
        return $match['key'] ?? $key;
    }

    public function handle()
    {
        $this->handleBefore();
        $this->buildModel();
        $this->unsetKeySaveCondition();
        $this->handleAfter();
    }

    protected function unsetKeySaveCondition()
    {
        if (empty($this->unsetKey)) return;
        $condition = $this->condition->getCondition();
        foreach ($this->unsetKey as $key) {
            unset($condition[$key]);
        }
        $this->condition->setCondition($condition);
    }

    protected function handleBefore()
    {
        $event = new QueryHandleBefore($this->condition);
        ApplicationContext::getContainer()->get(EventDispatcherInterface::class)->dispatch($event);
        $this->condition = $event->condition;
    }

    protected function handleAfter()
    {
        $event = new QueryHandleAfter($this->condition);
        ApplicationContext::getContainer()->get(EventDispatcherInterface::class)->dispatch($event);
        $this->condition = $event->condition;
    }

    protected function subTableQuery(array $data): QueryInterface
    {
        $tableName = $data['from'];
        $tableEntity = new TableEntity($tableName, $data);
        $conditionEntity = $tableEntity->getConditionEntity();
        $conditionEntity->setLimit(0);
        $handle = new Handle($conditionEntity, $tableEntity);
        $handle->build();
        /** @var QueryInterface $query */
        return new (ApplicationContext::getContainer()->get(ConfigInterface::class)->get(QueryInterface::class))($tableEntity->getRealTableName(), $tableEntity->getConditionEntity());
    }

    abstract protected function buildModel();
}