<?php
declare(strict_types=1);

namespace App\Listener;

use App\Constants\ConfigCode;
use Hyperf\Database\Events\StatementPrepared;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Utils\Context;

/**
 * @Listener
 */
class DbPreparedListener implements ListenerInterface
{
    public function listen(): array
    {
        return [
            StatementPrepared::class,
        ];
    }

    public function process(object $event)
    {
        if ($event instanceof StatementPrepared) {
            Context::set(ConfigCode::DB_QUERY_STATEMENT, $event->statement);
        }
    }
}