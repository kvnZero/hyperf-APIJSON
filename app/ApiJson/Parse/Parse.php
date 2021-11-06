<?php

namespace App\ApiJson\Parse;

use App\ApiJson\Entity\TableEntity;
use App\ApiJson\Method\AbstractMethod;
use App\ApiJson\Method\DeleteMethod;
use App\ApiJson\Method\GetMethod;
use App\ApiJson\Method\HeadMethod;
use App\ApiJson\Method\PostMethod;
use App\ApiJson\Method\PutMethod;
use Hyperf\Utils\Context;

class Parse
{
    protected array $tagColumn = [
        'tag' => null,
        'debug' => false
    ];

    protected array $globalKey = [
        'count' => null,
        'page' => null,
    ];

    protected array $supMethod = [
        GetMethod::class,
        HeadMethod::class,
        PostMethod::class,
        PutMethod::class,
        DeleteMethod::class
    ];

    protected array $tableEntities = [];

    public function __construct(protected array $json, protected string $method = 'GET', protected string $tag = '')
    {
    }

    public function handle(bool $isQueryMany = false, array $extendData = []): array
    {
        $result = [];
        foreach ($this->json as $tableName => $condition) { //可以优化成协程行为（如果没有依赖赋值的前提下）
            if (in_array($tableName, $this->filterKey())) {
                $this->tagColumn[$tableName] = $condition;
                continue;
            }
            if (in_array($tableName, $this->globalKey())) {
                $this->globalKey[$tableName] = $condition;
                continue;
            }
            if ($tableName == '[]' && $this->method == 'GET') {
                $result[$tableName] = $this->handleArray($condition, array_merge($result, $extendData)); //提供行为
                continue; //跳出不往下执行
            }
            if (str_ends_with($tableName, '[]')) {
                $isQueryMany = true;
            }
//            if (!preg_match("/^[A-Za-z]+$/", $tableName) || !is_array($condition)) {
//                continue; //不满足表名规范 跳出不往下执行
//            }
            $this->tableEntities[$tableName] = new TableEntity($tableName, $this->json, $this->getGlobalArgs(), array_merge($result, $extendData));
            foreach ($this->supMethod as $methodClass) {
                /** @var AbstractMethod $method */
                $method = new $methodClass($this->tableEntities[$tableName], $this->method);
                $method->setQueryMany($isQueryMany);
                $response = $method->handle();
                if (!is_null($response)) {
                    $result[$tableName] = $response;
                    break;
                }
            }
        }
        return $this->resultExtendHandle($result);
    }

    protected function resultExtendHandle(array $result)
    {
        if ($this->tagColumn['debug']) {
            $result['debug'] = (new Context())->get('querySql');
        }
        return $result;
    }

    /**
     * 处理[]的数据
     * @param array $jsonData
     * @param array $extendData
     * @return array
     */
    protected function handleArray(array $jsonData, array $extendData = []): array
    {
        $result = [[]];
        foreach ($jsonData as $tableName => $condition) { //可以优化成协程行为（如果没有依赖赋值的前提下）
            foreach ($result as $key => $item) {
                if (in_array($tableName, $this->filterKey())) {
                    $this->tagColumn[$tableName] = $condition;
                    continue;
                }
                if (in_array($tableName, $this->globalKey())) {
                    $this->globalKey[$tableName] = $condition;
                    continue;
                }
                $extendData['currentItem'] = $item;
                $this->tableEntities['[]'][$tableName] = new TableEntity($tableName, $jsonData, $this->getGlobalArgs(), array_merge($result, $extendData));
                $method = new GetMethod($this->tableEntities['[]'][$tableName], $this->method);
                $method->setQueryMany($result == [[]]);
                $response = $method->handle();
                if (!is_null($response)) {
                    if ($result == [[]]) {
                        $result = $response; //masterData
                    } else {
                        $result[$key][$tableName] = $response;
                    }
                }
            }
        }
        return $result;
    }

    protected function filterKey(): array
    {
        return array_keys($this->tagColumn);
    }

    protected function getGlobalArgs(): array
    {
        return array_filter($this->globalKey);
    }

    protected function globalKey(): array
    {
        return array_keys($this->globalKey);
    }
}