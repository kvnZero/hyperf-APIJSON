<?php

namespace App\ApiJson\Method;

use Hyperf\Utils\Arr;

class PostMethod extends AbstractMethod
{
    protected function validateCondition(): bool
    {
        return $this->method == 'POST';
    }

    protected function process()
    {
        $insertData = $this->tableEntity->getContent();
        $queryMany  = $this->isQueryMany();
        if (!$queryMany && Arr::isAssoc($insertData)) {
            $insertData = [$insertData];
        }
        if (!$queryMany) {
            $insertData = [$insertData[0]];
        }
        $insertIds = [];
        foreach ($insertData as $insertItem) {
            $insertIds[] = $this->query->insertGetId($insertItem); //因为需要返回ID 直接insert($insertData)不能得到本次插入的ID 未找到相关可用方法替代
        }
        return $this->parseManyResponse($insertIds, $this->isQueryMany());
    }
}