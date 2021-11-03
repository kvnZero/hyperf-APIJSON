<?php

namespace App\ApiJson\Interface;

use Closure;

interface QueryInterface
{
    public function __construct(string $tableName);

    public function where(string $column, $operator = null, $value = null, string $boolean = 'and'): self;

    public function whereIn(string $column, array $values, string $boolean = 'and', $not = false): self;

    public function whereRaw(string $sql, array $bindings = [], string $boolean = 'and'): self;

    public function whereExists(Closure $callback, string $boolean = 'and', $not = false): self;

    public function orderBy($column, $direction = 'asc'): self;

    public function groupBy(...$groups): self;

    public function select($columns = ['*']): self;

    public function having($column, $operator = null, $value = null, $boolean = 'and'): self;

    public function count($columns = '*'): int;

    public function limit(int $value): self;

    public function insertGetId(array $values, $sequence = null): int;

    public function update(array $values): bool;

    public function all();

    public function getDb();

    public function query();
}