<?php

namespace App\Event\ApiJson;

use App\ApiJson\Entity\ConditionEntity;

class QueryHandleAfter
{
    public function __construct(public ConditionEntity $condition)
    {
    }
}