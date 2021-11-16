<?php

namespace App\ApiJson\Method;

use App\ApiJson\Interface\QueryInterface;
use App\Event\ApiJson\QueryExecuteAfter;
use Hyperf\Utils\ApplicationContext;
use Hyperf\Utils\Arr;
use Psr\EventDispatcher\EventDispatcherInterface;

class DeleteMethod extends AbstractMethod
{
    protected function validateCondition(): bool
    {
        return $this->method == 'DELETE';
    }

    protected function process()
    {
        $ids = [];

        $jsonData = $this->tableEntity->getContent();
        $queryMany = $this->isQueryMany();
        if (isset($jsonData['id'])) {
            if (is_array($jsonData['id'])) {
                $ids = $jsonData['id'];
                $queryMany = true;
            } else {
                $ids = [$jsonData['id']];
            }
        } else if (isset($jsonData['id{}'])) {
            $ids = $jsonData['id{}']; //得到本次需要删除的ID
            $queryMany = true;
        }
        $deletedIds = [];
        foreach ($ids as $id) {
            $this->buildQuery();
            $this->query->delete($id) && $deletedIds[] = $id; //这里主键应可配置
        }
        $result = $this->parseManyResponse($deletedIds, $queryMany);

        $event = new QueryExecuteAfter($this->query->toSql(), $this->method, $result);
        ApplicationContext::getContainer()->get(EventDispatcherInterface::class)->dispatch($event);

        return $result;
    }
}