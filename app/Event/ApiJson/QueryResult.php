<?php

namespace App\Event\ApiJson;

use PDOStatement;

/**
 * 查询完毕的处理
 */
class QueryResult
{
    public function __construct(public array $result, public string $sql)
    {
    }
}