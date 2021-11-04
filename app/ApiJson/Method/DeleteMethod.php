<?php

namespace App\ApiJson\Method;

use App\ApiJson\Interface\QueryInterface;
use Hyperf\Utils\Arr;

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
        return $this->parseManyResponse($deletedIds, $queryMany);
    }
}