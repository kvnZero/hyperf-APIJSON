<?php

namespace App\ApiJson\Method;

use App\ApiJson\Parse\Handle;
use App\Event\ApiJson\QueryExecuteAfter;
use App\Event\ApiJson\QueryExecuteBefore;
use Hyperf\Utils\ApplicationContext;
use Psr\EventDispatcher\EventDispatcherInterface;

class GetMethod extends AbstractMethod
{
    protected function validateCondition(): bool
    {
        return $this->method == 'GET';
    }

    protected function process()
    {
        $handle = new Handle($this->tableEntity->getConditionEntity(), $this->tableEntity);
        $handle->build();

        $queryMany = $this->isQueryMany();
        if (!$queryMany) {
            $this->tableEntity->getConditionEntity()->setLimit(1);
        }

        //该事件鼓励是做语句缓存或者事件触发 不赞成修改语句做法 修改语句应在更上层的QueryHandle事件
        $event = new QueryExecuteBefore($this->query->toSql(), $this->method);
        ApplicationContext::getContainer()->get(EventDispatcherInterface::class)->dispatch($event);

        if(is_null($event->result)) {
            $result = $this->query->all();
        } else {
            $result = $event->result;
        }

        if ($queryMany) {
            foreach ($result as $key => $item) {
                $result[$key] = $this->arrayQuery ? [$this->tableEntity->getTableName() => $item] : $item;
            }
        } else {
            $result = current($result);
        }

        $event = new QueryExecuteAfter($this->query->toSql(), $this->method, $result);
        ApplicationContext::getContainer()->get(EventDispatcherInterface::class)->dispatch($event);

        return $result ?: [];
    }
}