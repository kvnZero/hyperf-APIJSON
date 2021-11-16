<?php
declare(strict_types=1);

namespace App\Listener;

use App\Event\ApiJson\QueryExecuteAfter;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;

/**
 * @Listener
 */
class QueryResultTryToJsonListener implements ListenerInterface
{
    public function listen(): array
    {
        return [
            QueryExecuteAfter::class,
        ];
    }

    public function process(object $event)
    {
        if (!$event instanceof QueryExecuteAfter) return;
        if ($event->method != 'GET') return;
        $event->result = $this->toJson($event->result);
    }

    private function toJson(array $result): array
    {
        foreach ($result as $key => $value) {
            if (is_array($value)) {
                $result[$key] = $this->toJson($value);
            }
            if (!is_string($value)) continue;
            $jsonData = json_decode($value, true);
            if (is_array($jsonData)) {
                $result[$key] = $jsonData;
            }
        }
        return $result;
    }
}