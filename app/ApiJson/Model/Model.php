<?php

namespace App\ApiJson\Model;

use Hyperf\Database\Query\Builder;
use Hyperf\DbConnection\Db;

class Model
{
    /** @var bool $queryMany */
    protected $queryMany = false;

    /** @var Builder */
    protected $db;

    /** @var string $primaryKey */
    protected $primaryKey = 'id';

    public function __construct(string $tableName)
    {
        $this->db = Db::table($tableName);
    }

    /**
     * @param string $primaryKey
     */
    public function setPrimaryKey(string $primaryKey): void
    {
        $this->primaryKey = $primaryKey;
    }

    /**
     * @return string
     */
    public function getPrimaryKey(): string
    {
        return $this->primaryKey;
    }

    /**
     * @return bool
     */
    public function isQueryMany(): bool
    {
        return $this->queryMany;
    }

    /**
     * @param bool $queryMany
     */
    public function setQueryMany(bool $queryMany): void
    {
        $this->queryMany = $queryMany;
    }

    public function getDb(): Builder
    {
        return $this->db;
    }

    public function getSql(): string
    {
        return $this->db->toSql();
    }

    public function Query(): array
    {
        return $this->db->get()->all();
    }
}