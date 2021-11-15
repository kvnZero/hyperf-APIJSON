<?php

namespace App\ApiJson\Method;

use App\ApiJson\Parse\Handle;
use App\Event\ApiJson\QueryExecuteAfter;
use App\Event\ApiJson\QueryExecuteBefore;
use Hyperf\Utils\ApplicationContext;
use Psr\EventDispatcher\EventDispatcherInterface;

class HeadMethod extends AbstractMethod
{
    protected function validateCondition(): bool
    {
        return $this->method == 'HEAD';
    }

    protected function process()
    {
        $handle = new Handle($this->tableEntity->getConditionEntity(), $this->tableEntity);
        $handle->build();

        $event = new QueryExecuteBefore($this->query->toSql(), $this->method);
        ApplicationContext::getContainer()->get(EventDispatcherInterface::class)->dispatch($event);

        if(is_null($event->result)) {
            $count = $this->query->count();
        } else {
            $count = $event->result;
        }

        $event = new QueryExecuteAfter($this->query->toSql(), $count);
        ApplicationContext::getContainer()->get(EventDispatcherInterface::class)->dispatch($event);

        return [
            'count' => $count
        ];
    }
}