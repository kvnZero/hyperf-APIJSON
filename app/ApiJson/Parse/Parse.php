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
        foreach ($this->json as $tableName => $condition) {
            if (in_array($tableName, $this->filterKey())) {
                $this->tagColumn[$tableName] = $condition; //特殊
                break;
            }
            $this->tableEntities[$tableName] = new TableEntity($tableName, $this->json);
            foreach ($this->supMethod as $methodClass) {
                /** @var AbstractMethod $method */
                $method = new $methodClass($this->tableEntities[$tableName], $this->method);
                $response = $method->handle();
                if (!is_null($response)) {
                    $result[$tableName] = $response;
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
}