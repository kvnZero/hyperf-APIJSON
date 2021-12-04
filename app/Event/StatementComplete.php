<?php


declare(strict_types=1);
/**
 * @author   kvnZero
 * @contact  kvnZero@github.com
 * @time     2021/12/4 5:25 下午
 */

namespace App\Event;

class StatementComplete
{
    /**
     * The database connection instance.
     *
     * @var \Hyperf\Database\Connection
     */
    public $connection;

    /**
     * The PDO statement.
     *
     * @var \PDOStatement
     */
    public $statement;

    /**
     * Create a new event instance.
     *
     * @param \Hyperf\Database\Connection $connection
     * @param \PDOStatement $statement
     */
    public function __construct($connection, $statement)
    {
        $this->statement = $statement;
        $this->connection = $connection;
    }
}