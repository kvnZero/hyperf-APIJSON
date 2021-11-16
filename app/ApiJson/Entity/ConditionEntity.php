<?php

namespace App\ApiJson\Entity;

class ConditionEntity
{
    protected array $changeLog = [];

    /**
     * @var array
     */
    protected array $where = [];

    protected int $limit = 10;
    protected int $offset = 0;
    protected array $column = ['*'];
    protected array $group = [];
    protected array $order = [];
    protected array $having = [];

    /**
     * @param array $condition 条件
     */
    public function __construct(protected array $condition, protected array $extendData = [])
    {
    }

    public function getExtendData(): array
    {
        return $this->extendData;
    }

    public function setCondition(array $condition)
    {
        $this->log($condition);
        $this->condition = $condition;
    }

    public function getCondition(): array
    {
        return $this->condition;
    }

    public function getQueryWhere(): array
    {
        return $this->where;
    }

    public function setQueryWhere(array $where)
    {
        $this->where = $where;
    }

    public function addQueryWhere(string $key, string $sql, array $bindArgs = [])
    {
        $this->where[$key] = [
            'sql' => $sql,
            'bind' => $bindArgs
        ];
    }

    /**
     * @param array|string[] $column
     */
    public function setColumn(array $column): void
    {
        $this->column = $column;
    }

    /**
     * @param array $group
     */
    public function setGroup(array $group): void
    {
        $this->group = $group;
    }

    /**
     * @param array $having
     */
    public function setHaving(array $having): void
    {
        $this->having = $having;
    }

    /**
     * @param int $limit
     */
    public function setLimit(int $limit): void
    {
        $this->limit = $limit;
    }

    /**
     * @param int $offset
     */
    public function setOffset(int $offset): void
    {
        $this->offset = $offset;
    }

    /**
     * @param array $order
     */
    public function setOrder(array $order): void
    {
        $this->order = $order;
    }

    /**
     * @return array
     */
    public function getColumn(): array
    {
        return $this->column;
    }

    /**
     * @return array
     */
    public function getGroup(): array
    {
        return $this->group;
    }

    /**
     * @return array
     */
    public function getHaving(): array
    {
        return $this->having;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @return array
     */
    public function getOrder(): array
    {
        return $this->order;
    }

    protected function log(array $condition)
    {
        $this->changeLog[] = [
            'old' => $this->condition,
            'new' => $condition
        ];
    }
}