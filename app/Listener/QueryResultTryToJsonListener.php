<?php
declare(strict_types=1);

namespace App\Listener;

use App\Event\StatementComplete;
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
            StatementComplete::class,
        ];
    }

    public function process(object $event)
    {
        if (!$event instanceof StatementComplete) return;
        if (empty($event->result)) return;
        $statement = $event->statement;

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