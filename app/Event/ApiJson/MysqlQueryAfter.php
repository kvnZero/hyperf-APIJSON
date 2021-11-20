<?php

namespace App\Event\ApiJson;

use PDOStatement;

/**
 * 查询完毕的处理
 */
class MysqlQueryAfter
{
    public function __construct(public array $result, public PDOStatement $statement, protected string $sql, protected array $bindings)
    {
    }
}