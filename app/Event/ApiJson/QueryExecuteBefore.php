<?php

namespace App\Event\ApiJson;

/**
 * 查询(GET, HEAD)相关语句执行前会执行该事件
 */
class QueryExecuteBefore
{
    public $result = null; //如果这里被赋值 则不会执行代码 而会直接抛出该结果

    public function __construct(public string $sql, public string $method)
    {
    }
}