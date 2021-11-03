<?php

namespace App\ApiJson\Entity;

class TableEntity
{
    /** @var ConditionEntity $ConditionEntity */
    protected ConditionEntity $conditionEntity;

    /** @var string $realTableName 真实表名 */
    protected string $realTableName;

    /**
     * @param string $tableName 表名
     * @param array $jsonContent json数据
     */
    public function __construct(protected string $tableName, protected array $jsonContent)
    {
        $this->realTableName = $tableName;
    }

    protected function parseConditionEntity()
    {
        $entity = new ConditionEntity(
            $this->getConditionByContent()
        );
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