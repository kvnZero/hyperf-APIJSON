<?php

namespace App\ApiJson\Parse;

use App\ApiJson\Entity\TableEntity;
use App\ApiJson\Method\AbstractMethod;
use App\ApiJson\Method\DeleteMethod;
use App\ApiJson\Method\GetMethod;
use App\ApiJson\Method\HeadMethod;
use App\ApiJson\Method\PostMethod;
use App\ApiJson\Method\PutMethod;
use App\Constants\ResponseCode;

class Parse
{
    protected array $tagColumn = [
        'tag' => null
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

    public function handle(): array
    {
        $result = [
            'code' => ResponseCode::SUCCESS,
            'msg' => ResponseCode::getMessage(ResponseCode::SUCCESS),
        ];
        $this->jsonParse();
        foreach ($this->json as $tableName => $condition) { //可以优化成协程行为（如果没有依赖赋值的前提下）
            $this->tableEntities[$tableName] = new TableEntity($tableName, $this->json, $result);
            foreach ($this->supMethod as $methodClass) {
                /** @var AbstractMethod $method */
                $method = new $methodClass($this->tableEntities[$tableName], $this->method);
                $response = $method->handle();
                if (!is_null($response)) {
                    $result[$condition['*'] ?? $tableName] = $response;
                    break;
                }
            }
        }
        //TODO: 抽象操作方法
        return $result;
    }

    /**
     * 整理不同形式的json数据到统一格式再处理
     */
    protected function jsonParse()
    {
        foreach ($this->json as $tableName => $condition) { //可以优化成协程行为（如果没有依赖赋值的前提下）
            if (in_array($tableName, $this->filterKey())) {
                $this->tagColumn[$tableName] = $condition; //特殊
                unset($this->json[$tableName]);
                break;
            }
            if ($tableName == '[]') {
                $count = (int)$condition['count'] ?? 10;
                $page = (int)$condition['page'] ?? 1; //赋予默认值
                $tableName = array_key_first($condition); //剩下的值为table
                $condition = $condition[$tableName]; //替换条件
                $condition = array_merge($condition, [
                    '@limit' => $count, //查询长度
                    '@offset' => $page * $count, //查询起始长度
                    '*' => '[]' //赋予替换表明的标志
                ]);
                $this->json[$tableName . '[]'] = $condition;
            }
        }
    }

    protected function filterKey(): array
    {
        return array_keys($this->tagColumn);
    }
}