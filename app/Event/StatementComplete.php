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
     * The query result
     *
     * @var array|false
     */
    public $result;

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
     * @param array|false $result
     * @param \PDOStatement $statement
     */
    public function __construct($connection, $result, $statement)
    {
        $this->statement = $statement;
        $this->result = $result;
        $this->connection = $connection;
    }
}