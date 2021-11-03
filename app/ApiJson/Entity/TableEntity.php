<?php

namespace App\ApiJson\Entity;

use App\ApiJson\Interface\QueryInterface;

class TableEntity
{
    /** @var ConditionEntity $ConditionEntity */
    protected ConditionEntity $conditionEntity;

    /** @var string $realTableName 真实表名 */
    protected string $realTableName;

    /** @var array $condition */
    protected array $condition;

    /** @var QueryInterface $query */
    protected QueryInterface $query;

    /**
     * @param string $tableName 表名
     * @param array $jsonContent json数据
     */
    public function __construct(protected string $tableName, protected array $jsonContent)
    {
        $this->realTableName = $tableName;
        $this->condition = $this->getConditionByContent();
    }

    public function getResult(): array
    {
        $this->buildQuery();
        $this->parseConditionEntity();
        return $this->formatResult($this->query->all());
    }

    public function getCount(): int
    {
        $this->buildQuery();
        $this->parseConditionEntity();
        return $this->query->count();
    }

    public function insert()
    {

    }

    public function update()
    {

    }

    protected function formatResult(array $result): array
    {
        return $result;
    }

    protected function buildQuery()
    {
        $this->query = new (config(join('.', [
            'dependencies', QueryInterface::class
        ])))($this->realTableName);
    }

    protected function parseConditionEntity()
    {
        $entity = new ConditionEntity($this->condition);
        $this->conditionEntity = $entity;
    }

    protected function getConditionByContent(): array
    {
        $sanitizeTableName = str_replace(['[]'], '', $this->tableName);
        if (isset($this->jsonContent[$sanitizeTableName])) {
            $this->realTableName = $sanitizeTableName;
            return $this->jsonContent[$sanitizeTableName];
        }
        return $this->jsonContent[$this->tableName];
    }
}