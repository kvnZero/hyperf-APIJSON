<?php

namespace App\ApiJson\Handle;

use App\ApiJson\Entity\ConditionEntity;
use App\ApiJson\Interface\QueryInterface;
use App\Event\ApiJson\QueryHandleAfter;
use App\Event\ApiJson\QueryHandleBefore;
use Hyperf\Utils\ApplicationContext;
use Psr\EventDispatcher\EventDispatcherInterface;

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

    abstract protected function buildModel();
}