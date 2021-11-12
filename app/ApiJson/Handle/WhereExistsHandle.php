<?php

namespace App\ApiJson\Handle;

use App\ApiJson\Entity\TableEntity;
use App\ApiJson\Interface\QueryInterface;
use App\ApiJson\Parse\Handle;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Database\Query\Builder;
use Hyperf\Utils\ApplicationContext;

class WhereExistsHandle extends AbstractHandle
{
    protected function buildModel()
    {
        foreach (array_filter($this->condition->getCondition(), function($key){
            return str_ends_with($key, '}{@');
        }, ARRAY_FILTER_USE_KEY) as $key => $value)
        {
            $bind = [];
            $existsSql = '';

            $tableName = $value['from'];
            $tableEntity = new TableEntity($tableName, $value[$value['from']]);
            $query = new (ApplicationContext::getContainer()->get(ConfigInterface::class)->get(QueryInterface::class))($tableEntity->getRealTableName());
            $handle = new Handle($tableEntity->getConditionEntity(), $tableEntity);
            $handle->build($query);
            $queryWhere = $tableEntity->getConditionEntity()->getQueryWhere();
//
//
//            foreach ($value[$value['from']] as $k => $v) {
//                $query->where($k, $v);
//            }
//            $sql = sprintf("WHERE EXISTS %s", $existsSql);
//            $this->query->whereExists(function(Builder $query) use($value) {
//
//            });
            $this->unsetKey[] = $key;
        }
    }
}