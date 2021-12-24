<?php

namespace App\ApiJson\Model;

use App\ApiJson\Entity\ConditionEntity;
use App\ApiJson\Interface\QueryInterface;
use Hyperf\Database\Query\Builder;
use Hyperf\DbConnection\Db;

class MysqlQuery implements QueryInterface
{
    /** @var int 查询行为 */
    const ACTION_QUERY = 1;

    /** @var int 修改行为 */
    const ACTION_UPDATE = 2;

    /** @var int 插入行为 */
    const ACTION_INSERT = 4;


    /** @var string $primaryKey */
    protected string $primaryKey = 'id';

    /** @var bool $build 是否已经生成条件 */
    protected bool $build = false;

    /** @var Builder $db */
    protected Builder $db;

    public function __construct(protected string $tableName, protected ConditionEntity $conditionEntity)
    {
        $this->db = Db::table($tableName);
    }

    public function getDb(): Builder
    {
        return $this->db;
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

    public function all(): array
    {
        $this->buildQuery();
        return $this->db->get()->all();
    }

    public function count($columns = '*'): int
    {
        $this->buildQuery();
        return $this->db->count();
    }

    public function toSql(): string
    {
        $this->buildQuery();
        return $this->db->toSql();
    }

    public function insert(array $values, $sequence = null): int
    {
        $this->build = true;
        return $this->db->insertGetId($values, $sequence);
    }

    public function update(array $values): bool
    {
        $this->build = true;
        $this->buildQuery(self::ACTION_UPDATE);
        if (empty($this->db->getBindings()['where'])) return false; // 不允许空条件修改
        return $this->db->update($values);
    }

    public function delete($id = null): bool
    {
        $this->build = true;
        return $this->db->delete($id);
    }

    public function getBindings(): array
    {
        return $this->db->getBindings();
    }

    public function callProcedure(array $dataItem, bool $query = true): array
    {
        if (empty($this->conditionEntity->getProcedure())) {
            return [];
        }
        $count = -1;
        $list = []; //默认值

        $procedure = $this->conditionEntity->getProcedure();
        $rule = '/(?<functionName>.+)\((?<args>.+?)\)/';
        preg_match($rule, $procedure, $match);
        if (empty($match['functionName'])) {
            return [];
        }
        $args = array_map('trim', explode(',', $match['args']));

        $callArgs = [];
        foreach ($args as $arg) {
            if (in_array($arg, array_keys($dataItem))) {
                $callArgs[] = $dataItem[$arg];
            } else if ($arg == '@limit') {
                $callArgs[] = $this->conditionEntity->getLimit();
            } else if ($arg == '@offset') {
                $callArgs[] = $this->conditionEntity->getOffset();
            } else {
                $callArgs[] = $arg;
            }
        }

        $sql = sprintf("CALL %s(%s)", $match['functionName'], join(',', $callArgs));

        if ($query) {
            $list = Db::select($sql);
        } else {
            $count = Db::affectingStatement($sql);
        }

        return [
            'count' => $count,
            'update' => !$query,
            'list' => $list,
        ];
    }

    protected function buildQuery(int $actionType = self::ACTION_QUERY)
    {
        if ($this->build) return;
        $this->build = true;
        $queryWhere = $this->conditionEntity->getQueryWhere();
        foreach ($queryWhere as $itemWhere) {
            $this->db->whereRaw($itemWhere['sql'], $itemWhere['bind']);
        }
        if ($actionType != self::ACTION_QUERY) return; //下面不再非查询操作

        $this->db->select(Db::raw($this->conditionEntity->getColumn()));
        $limit = $this->conditionEntity->getLimit();
        if ($limit > 0) {
            $this->db->limit($limit);
        }
        $offset = $this->conditionEntity->getOffset();
        if ($offset > 0) {
            $this->db->offset($offset);
        }
        $group = $this->conditionEntity->getGroup();
        if (!empty($group)) {
            $this->db->groupBy(...$group);
        }
        $orderArr = $this->conditionEntity->getOrder();
        if (!empty($orderArr)) {
            foreach ($orderArr as $orderItem) {
                $this->db->orderBy($orderItem[0], $orderItem[1]);
            }
        }
        $havingArr = $this->conditionEntity->getHaving();
        if (!empty($havingArr)) {
            foreach ($havingArr as $havingItem) {
                $this->db->having($havingItem);
            }
        }
    }
}