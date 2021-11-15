<?php

namespace App\Event\ApiJson;

use App\ApiJson\Entity\ConditionEntity;

class QueryHandleBefore
{
    public function __construct(public ConditionEntity $condition)
    {
    }
}