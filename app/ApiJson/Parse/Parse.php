<?php

namespace App\ApiJson\Parse;

use App\ApiJson\Model\Model;
use App\Constants\ResponseCode;
use Hyperf\Database\Query\Builder;
use Hyperf\Utils\Arr;
use Laminas\Stdlib\ArrayUtils;

class Parse
{
    /** @var string $tableName */
    protected $tableName;

    /** @var array $json */
    protected $json;

    /** @var string $method */
    protected $method;

    /** @var string $tag */
    protected $tag;

    public function __construct($json, $method = 'GET', $tag = '')
    {
        if (is_string($json)) {
            $this->json = json_decode($json, true);
        } else {
            $this->json = $json;
        }
        $this->method = $method;
        $this->tag = $tag;
    }

    public function handle(): array
    {
        $model = $this->parseModel();
        if (in_array($this->method, ['GET', 'HEAD'])) {
            $model = $this->parseQueryJson($model, $this->json);
        }
        return [$this->tableName => $this->parseResponse($model)];
    }

    protected function handleGet(Model $model): array
    {
        return $model->getDb()->get()->all();
    }

    protected function handleHead(Model $model): array
    {
        return [
            'code' => ResponseCode::SUCCESS,
            'msg' => ResponseCode::getMessage(ResponseCode::SUCCESS),
            'count' => $model->getDb()->count(),
        ];
    }

    protected function handlePost(Model $model): array
    {
        $insertData = $this->json;
        if (!$model->isQueryMany() || Arr::isAssoc($insertData)) {
            $insertData = [$insertData];
        }
        $insertIds = [];
        foreach ($insertData as $insertItem) {
            $insertIds[] = $model->getDb()->insertGetId($insertItem); //因为需要返回ID 直接insert($insertData)不能得到本次插入的ID 未找到相关可用方法替代
        }

        return $this->parseManyResponse($insertIds, $model->isQueryMany());
    }

    protected function handlePut(Model $model): array
    {
        $updateData = $this->json;
        if (!$model->isQueryMany() || Arr::isAssoc($updateData)) {
            $updateData = [$updateData]; //先将单数据转成多数据
        }
        $updateIds = [];
        foreach ($updateData as $updateItem) {
            $ids = [];
            if (isset($updateItem['id'])) {
                $ids = [$updateItem['id']];
            } else if (isset($updateItem['id{}'])) {
                $ids = $updateItem['id{}']; //得到本次需要更新的ID
                $model->setQueryMany(true); //设置为操作多次
            }
            unset($updateItem['id'], $updateItem['id{}']);

            $model->getDb()->where($model->getPrimaryKey(), 0); //定义更新绑定语句
            foreach ($ids as $id) {
                $model->getDb()->setBindings([$id])->update($updateItem) && $updateIds[] = $id;
            }
        }

        return $this->parseManyResponse($updateIds, $model->isQueryMany());
    }

    protected function handleDelete(Model $model): array
    {
        $ids = [];
        if (isset($this->json['id'])) {
            $ids = [$this->json['id']];
        } else if (isset($updateItem['id{}'])) {
            $ids = $this->json['id{}']; //得到本次需要删除的ID
            $model->setQueryMany(true); //设置为删除多次
        }
        $deletedIds = [];
        foreach ($ids as $id) {
            $model->getDb()->newQuery()->delete($id) && $deletedIds[] = $id; //这里主键应可配置
        }

        return $this->parseManyResponse($deletedIds, $model->isQueryMany());
    }

    protected function parseResponse(Model $model)
    {
        $methodMap = [
            'GET' => 'handleGet',
            'HEAD' => 'handleHead',
            'POST' => 'handlePost',
            'PUT' => 'handlePut',
            'DELETE' => 'handleDelete'
        ];
        return $this->{$methodMap[strtoupper($this->method)]}($model);
    }

    protected function parseManyResponse(array $ids, bool $isQueryMany = false): array
    {
        $response = [
            'code' => !empty($ids) ? ResponseCode::SUCCESS : ResponseCode::SERVER_ERROR,
            'msg' => ResponseCode::getMessage(!empty($ids) ? ResponseCode::SUCCESS : ResponseCode::SERVER_ERROR),
        ];
        if ($isQueryMany) {
            $response = array_merge($response, [
                'id[]' => $ids,
                'count' => count($ids)
            ]);
        } else {
            $response['id'] = current($ids) ?: 0;
        }
        return $response;
    }

    protected function parseModel(): Model
    {
        $json = $this->json;
        if (!empty($this->tag)) {
            $this->tableName = $this->tag;
        } else {
            $this->tableName = array_key_first($json);
            $this->tag = $this->tableName;
        }
        $queryMany = str_ends_with($this->tableName, '[]'); //是否数组行为
        if ($queryMany) {
            $this->tableName = str_replace('[]', '', $this->tableName);
        }

        $this->json = $json[$this->tableName] ?? $json[$this->tableName . '[]']; //因为看到文档的delete有tag是[], 但表名并不带[]的问题
        while (isset($this->json[$this->tableName])) {
            $this->json = $this->json[$this->tableName]; //根据3.2.1查询数组的意义
        }

        $model = new Model($this->tableName);
        $model->setQueryMany($queryMany);
        return $model;
    }

    protected function parseQueryJson(Model $model, array $json): Model
    {
        foreach ($json as $key => $value) {
            $queryKey = str_replace([
                '{}', '<>', '}{@', '$', '%', '~'
            ], '', $key);
            //只剩查询条件
            if (str_ends_with($key, '{}') && is_array($value)) {
                $model->getDb()->whereIn($queryKey, $value); //3.2.2
            } else if (str_ends_with($key, '{}') && is_string($value)) {
                $conditionArr = explode(',', $value);
                $sql = [];
                foreach ($conditionArr as $condition) {
                    $sql[] = sprintf("`%s`%s", $queryKey, trim($condition));
                }
                $model->getDb()->whereRaw(join(' OR ', $sql)); //3.2.3
            } else if (str_ends_with($key, '}{@')) {
                $model->getDb()->whereExists(function(Builder $query) use ($value){ //3.2.5
                    $query = $query->from($value['from']);
                    foreach ($value[$value['from']] as $k => $v) {
                        $query->where($k, $v);
                    }
                });
            } else if (str_ends_with($key, '<>') || str_ends_with($key, '$') || str_ends_with($key, '~') || str_ends_with($key, '%')) {
                $value = !is_array($value) ? [$value] : $value;
                $sql = [];
                if (str_ends_with($key, '<>')) {
                    foreach ($value as $item) {
                        $sql[] = sprintf("json_contains(%s,%s)", $queryKey, trim($item));
                    }
                } else if (str_ends_with($key, '$')) {
                    foreach ($value as $item) {
                        $sql[] = sprintf("%s LIKE %s", $queryKey, trim($item));
                    }
                } else if (str_ends_with( $key, '~')) {
                    foreach ($value as $item) {
                        $sql[] = sprintf("%s REGEXP %s", $queryKey, trim($item));
                    }
                } else if (str_ends_with($key, '%')) {
                    foreach ($value as $item) {
                        $itemArr = explode(',', $item);
                        $sql[] = sprintf("%s BETWEEN %s AND %s", $queryKey, trim($itemArr[0]), trim($itemArr[1]));
                    }
                }
                $model->getDb()->whereRaw(join(' OR ', $sql));
            } else if (str_starts_with($key, '@')) {
                switch ($key) {
                    case '@column':
                        $value = str_replace([';',':'], [',', ' AS '], $value);
                        $model->getDb()->select(explode(',', $value));
                        break;
                    case '@order':
                        $orderArr = explode(',', $value);
                        foreach ($orderArr as $order) {
                            $model->getDb()->orderBy(str_replace(['-', '+'], '', $order), str_ends_with($order, '-') ? 'desc' : 'asc');
                        }
                        break;
                    case '@group':
                        $groupArr = explode(',', $value);
                        foreach ($groupArr as $group) {
                            $model->getDb()->groupBy($group);
                        }
                        break;
                    case '@having':
                        $havingArr = explode(';', $value);
                        foreach ($havingArr as $having) {
                            $model->getDb()->havingRaw($having);
                        }
                }
            }
        }
        return $model;
    }


}