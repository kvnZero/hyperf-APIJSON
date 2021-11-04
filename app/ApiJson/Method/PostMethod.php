<?php

namespace App\ApiJson\Method;

use App\ApiJson\Interface\QueryInterface;
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
        if (!$this->isQueryMany() || Arr::isAssoc($insertData)) {
            $insertData = [$insertData];
        }
        $insertIds = [];
        foreach ($insertData as $insertItem) {
            $insertIds[] = $this->query->insertGetId($insertItem); //因为需要返回ID 直接insert($insertData)不能得到本次插入的ID 未找到相关可用方法替代
        }
        return $this->parseManyResponse($insertIds, $this->isQueryMany());
    }
}