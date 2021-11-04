<?php

namespace App\ApiJson\Method;

use Hyperf\Utils\Arr;

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
                $this->query->where($this->query->getPrimaryKey(), '=', $id)->update($updateItem) && $updateIds[] = $id;
            }
        }
        return $this->parseManyResponse($updateIds, $queryMany);
    }
}