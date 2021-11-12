<?php

namespace App\ApiJson\Interface;

use App\ApiJson\Entity\ConditionEntity;

interface QueryInterface
{
    public function __construct(string $tableName, ConditionEntity $conditionEntity);

    public function setPrimaryKey(string $primaryKey): void;

    public function getPrimaryKey(): string;

    public function count($columns = '*'): int;

    public function insertGetId(array $values, $sequence = null): int;

    public function update(array $values): bool;

    public function delete($id = null): bool;

    public function all();

    public function toSql();

    public function getBindings();

    public function getDb();

    public function query();
}