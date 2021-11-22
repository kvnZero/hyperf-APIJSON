<?php
declare(strict_types=1);

namespace App\Listener;

use App\Constants\ConfigCode;
use App\Event\ApiJson\QueryResult;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Utils\Context;

/**
 * @Listener
 */
class QueryResultTryToJsonListener implements ListenerInterface
{
    public function listen(): array
    {
        return [
            QueryResult::class,
        ];
    }

    public function process(object $event)
    {
        if (!$event instanceof QueryResult) return;

        $statement = Context::get(ConfigCode::DB_QUERY_STATEMENT);
        if (empty($statement)) {
            return;
        }

        $columnCount = count(array_keys(current($event->result)));
        $columnMeta = [];
        for ($i = 0; $i <= $columnCount; $i++) {
            $meta = $statement->getColumnMeta($i);
            if ($meta) {
                $columnMeta[$meta['name']] = $meta;
            }
        }

        foreach(array_filter($columnMeta, function ($item) {
            return !isset($item['native_type']) && in_array('blob', $item['flags']);
        }, ARRAY_FILTER_USE_BOTH) as $item) {
            for ($i = 0; $i < count($event->result); $i++) {
                if (!is_string($event->result[$i][$item['name']])) continue;
                $jsonData = json_decode($event->result[$i][$item['name']], true);
                if (is_array($jsonData)) {
                    $event->result[$i][$item['name']] = $jsonData;
                }
            }
        }
    }
}