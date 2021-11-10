<?php

namespace App\ApiJson\Method;

use App\ApiJson\Entity\TableEntity;
use App\ApiJson\Interface\QueryInterface;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Utils\ApplicationContext;

abstract class AbstractMethod
{
    /** @var QueryInterface $query */
    protected QueryInterface $query;

    /** @var bool $isQueryMany */
    protected bool $isQueryMany = false;

    /** @var bool $arrayQuery */
    protected bool $arrayQuery = false;

    public function __construct(protected TableEntity $tableEntity, protected string $method = 'GET')
    {
        $this->buildQuery();
        $this->isQueryMany = str_ends_with($this->tableEntity->getTableName(), '[]');
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
        if ($isQueryMany) {
            $response = [
                'id[]' => $ids,
                'count' => count($ids)
            ];
        } else {
            $response['id'] = current($ids) ?: 0;
        }
        return $response;
    }

    public function setQueryMany(bool $isQueryMany = false)
    {
        $this->isQueryMany = $isQueryMany;
    }

    public function setArrayQuery(bool $arrayQuery = false)
    {
        $this->arrayQuery = $arrayQuery;
    }

    protected function isQueryMany(): bool
    {
        return $this->isQueryMany; //可能有不止一个因素影响
    }

    abstract protected function validateCondition(): bool;

    abstract protected function process();
}