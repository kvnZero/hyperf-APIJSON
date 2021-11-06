<?php

namespace App\ApiJson\Model;

use App\ApiJson\Interface\QueryInterface;
use Closure;
use Hyperf\Database\Query\Builder;
use Hyperf\DbConnection\Db;

class MysqlQuery implements QueryInterface
{
    /** @var string $primaryKey */
    protected string $primaryKey = 'id';

    /** @var Builder $db */
    protected Builder $db;

    public function __construct(protected string $tableName)
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

    public function getDb(): Builder
    {
        return $this->db;
    }

    public function query(): array
    {
        return $this->db->get()->all();
    }

    public function where(string $column, $operator = null, $value = null, string $boolean = 'and'): self
    {
        $this->db->where($column, $operator, $value, $boolean);
        return $this;
    }

    public function whereIn(string $column, array $values, string $boolean = 'and', $not = false): self
    {
        $this->db->whereIn($column, $values, $boolean, $not);
        return $this;
    }

    public function whereRaw(string $sql, array $bindings = [], string $boolean = 'and'): self
    {
        $this->db->whereRaw($sql, $bindings, $boolean);
        return $this;
    }

    public function whereExists(Closure $callback, string $boolean = 'and', $not = false): self
    {
        $this->db->whereExists($callback, $boolean, $not);
        return $this;
    }

    public function orderBy($column, $direction = 'asc'): self
    {
        $this->db->orderBy($column, $direction);
        return $this;
    }

    public function groupBy(...$groups): self
    {
        $this->db->groupBy(...$groups);
        return $this;
    }

    public function select($columns = ['*']): self
    {
        $this->db->select($columns);
        return $this;
    }

    public function count($columns = '*'): int
    {
        return $this->db->count();
    }

    public function insertGetId(array $values, $sequence = null): int
    {
        return $this->db->insertGetId($values, $sequence);
    }

    public function update(array $values): bool
    {
        return $this->db->update($values);
    }

    public function delete($id = null): bool
    {
        return $this->db->delete($id);
    }

    public function having($column, $operator = null, $value = null, $boolean = 'and'): self
    {
       $this->db->having($column, $operator, $value, $boolean);
       return $this;
    }

    public function limit(int $value): self
    {
        $this->db->limit($value);
        return $this;
    }

    public function offset(int $value): self
    {
        $this->db->offset($value);
        return $this;
    }

    public function all(): array
    {
        return $this->db->get()->all();
    }
}