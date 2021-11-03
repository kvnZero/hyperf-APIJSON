<?php

namespace App\ApiJson\Model;

interface QueryInterface
{
    public function where(string $column, $operator = null, $value = null, string $boolean = 'and');

    public function whereIn(string $column, array $values, string $boolean = 'and', $not = false);

    public function whereRaw(string $sql, array $bindings = [], string $boolean = 'and');

    public function whereExists(\Closure $callback, string $boolean = 'and', $not = false);
}