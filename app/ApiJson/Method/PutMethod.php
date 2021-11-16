<?php

namespace App\ApiJson\Method;

use App\Event\ApiJson\QueryExecuteAfter;
use Hyperf\Utils\ApplicationContext;
use Hyperf\Utils\Arr;
use Psr\EventDispatcher\EventDispatcherInterface;

class PutMethod extends AbstractMethod
{
    protected function validateCondition(): bool
    {
        return $this->method == 'PUT';
    }

    protected function process()
    {
        $updateData = $this->tableEntity->getContent();
        $queryMany = $this->isQueryMany();
        if (!$queryMany || Arr::isAssoc($updateData)) {
            $updateData = [$updateData];
        }
        $updateIds = [];
        foreach ($updateData as $updateItem) {
            $ids = [];
            if (isset($updateItem['id'])) {
                if (is_array($updateItem['id'])) {
                    $ids = $updateItem['id'];
                    $queryMany = true;
                } else {
                    $ids = [$updateItem['id']];
                }
            } else if (isset($updateItem['id{}'])) {
                $ids = $updateItem['id{}']; //得到本次需要更新的ID
                $queryMany = true;
            }
            unset($updateItem['id'], $updateItem['id{}']);

            foreach ($ids as $id) {
                $this->buildQuery();
                $querySql = sprintf('`%s` = ?', $this->query->getPrimaryKey());
                $this->tableEntity->getConditionEntity()->addQueryWhere('id', $querySql, [$id]);
                $this->query->update($updateItem) && $updateIds[] = $id;
            }
        }
        $result = $this->parseManyResponse($updateIds, $queryMany);

        $event = new QueryExecuteAfter($this->query->toSql(), $this->method, $result);
        ApplicationContext::getContainer()->get(EventDispatcherInterface::class)->dispatch($event);

        return $event->result;
    }
}