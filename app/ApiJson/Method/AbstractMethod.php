<?php

namespace App\ApiJson\Method;

use App\ApiJson\Entity\TableEntity;
use App\ApiJson\Interface\QueryInterface;
use App\Constants\ResponseCode;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Utils\ApplicationContext;

abstract class AbstractMethod
{
    /** @var QueryInterface $query */
    protected QueryInterface $query;

    public function __construct(protected TableEntity $tableEntity, protected string $method = 'GET')
    {
        $this->buildQuery();
    }

    public function handle(): ?array
    {
        if (!$this->validateCondition()) return null;
        return $this->process();
    }

    protected function buildQuery()
    {

        $this->query = new (ApplicationContext::getContainer()->get(ConfigInterface::class)->get(QueryInterface::class))($this->tableEntity->getRealTableName());
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

    protected function isQueryMany(): bool
    {
        return str_ends_with($this->tableEntity->getTableName(), '[]');
    }

    abstract protected function validateCondition(): bool;

    abstract protected function process();
}