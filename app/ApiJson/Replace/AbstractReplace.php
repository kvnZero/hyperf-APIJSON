<?php

namespace App\ApiJson\Replace;

use App\ApiJson\Entity\ConditionEntity;

abstract class AbstractReplace
{
    public function __construct(protected ConditionEntity $condition)
    {
    }

    public function handle(): ?array
    {
        return $this->process();
    }

    abstract protected function process();
}