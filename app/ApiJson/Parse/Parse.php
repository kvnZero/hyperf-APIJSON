<?php

namespace App\ApiJson\Parse;

use App\ApiJson\Entity\TableEntity;
use App\ApiJson\Method\AbstractMethod;
use App\ApiJson\Method\DeleteMethod;
use App\ApiJson\Method\GetMethod;
use App\ApiJson\Method\HeadMethod;
use App\ApiJson\Method\PostMethod;
use App\ApiJson\Method\PutMethod;

class Parse
{
    protected array $tagColumn = [
        'tag' => null
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

    public function handle(bool $isQueryMany = false): array
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
            if ($tableName == '[]') {
                $parse = new self($condition, $this->method, $condition['tag'] ?? '');
                $result[$tableName] = $parse->handle(true); //提供行为
                continue; //跳出不往下执行
            }
            $this->tableEntities[$tableName] = new TableEntity($tableName, $this->json, $this->getGlobalArgs(), $result);
            foreach ($this->supMethod as $methodClass) {
                /** @var AbstractMethod $method */
                $method = new $methodClass($this->tableEntities[$tableName], $this->method);
                $method->setQueryMany($isQueryMany);
                $response = $method->handle();
                if (!is_null($response)) {
                    if ($isQueryMany) {
                        $result = $response;
                    } else {
                        $result[$tableName] = $response;
                    }
                    break;
                }
            }
        }
        //TODO: 抽象操作方法
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