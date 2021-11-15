<?php

namespace App\Event\ApiJson;

/**
 * 任何方法都会执行该事件
 */
class QueryExecuteAfter
{
    public function __construct(public string $sql, public $result)
    {
    }
}