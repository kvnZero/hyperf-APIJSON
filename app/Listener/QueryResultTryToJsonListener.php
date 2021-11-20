<?php
declare(strict_types=1);

namespace App\Listener;

use App\Event\ApiJson\MysqlQueryAfter;
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
            MysqlQueryAfter::class,
        ];
    }

    public function process(object $event)
    {
        if (!$event instanceof MysqlQueryAfter) return;
        $columnCount = count(array_keys(current($event->result)));
        $columnMeta = [];
        for ($i = 0; $i <= $columnCount; $i++) {
            $meta = $event->statement->getColumnMeta($i);
            if ($meta) {
                $columnMeta[$meta['name']] = $meta;
            }
        }

        foreach(array_filter($columnMeta, function ($item) {
            return !isset($item['native_type']) && in_array('blob', $item['flags']);
        }, ARRAY_FILTER_USE_BOTH) as $item) {
            for ($i = 0; $i < count($event->result); $i++) {
                $event->result[$i][$item['name']] = json_decode($event->result[$i][$item['name']], true);
            }
        }
    }
}