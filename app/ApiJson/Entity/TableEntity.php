<?php

namespace App\ApiJson\Entity;

class TableEntity
{
    /** @var ConditionEntity $ConditionEntity */
    protected ConditionEntity $conditionEntity;

    /** @var string $realTableName 真实表名 */
    protected string $realTableName;

    /** @var array $content 表名对应的数据 */
    protected array $content;

    /**
     * @param string $tableName 表名
     * @param array $jsonContent json源数据
     */
    public function __construct(protected string $tableName, protected array $jsonContent, protected array $globalArgs = [], protected array $extendData = [])
    {
        $sanitizeTableName = str_replace(['[]'], '', $this->tableName);
        $this->realTableName = $sanitizeTableName;
        $this->content = $this->getContentByTableName();
        $this->parseConditionEntity();
    }

    public function getTableName(): string
    {
        return $this->tableName;
    }

    public function getRealTableName(): string
    {
        return $this->realTableName;
    }

    public function getContent(): array
    {
        return $this->content;
    }

    public function getConditionEntity(): ConditionEntity
    {
        return $this->conditionEntity;
    }

    protected function getContentByTableName(): array
    {
        $content = $this->jsonContent[$this->tableName];
        if (isset($content[$this->realTableName])) $content = $content[$this->realTableName];
        return $content;
    }

    protected function parseConditionEntity()
    {
        $entity = new ConditionEntity(array_merge($this->globalArgs, $this->content), $this->extendData);
        $this->conditionEntity = $entity;
    }
}