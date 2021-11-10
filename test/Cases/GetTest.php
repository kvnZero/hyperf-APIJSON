<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace HyperfTest\Cases;

use App\ApiJson\Parse\Parse;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
class GetTest extends TestCase
{
    protected string $method = 'GET';

    public function testColumn()
    {
        $json = [
            'User' => [
                'id' => 1,
                '@column' => 'id'
            ]
        ];
        $parse = new Parse($json, $this->method, '');
        $result = $parse->handle();

        $this->assertSame([
            'User' => [
                'id' => 1
            ]
        ], $result);
    }

    public function testWhere()
    {
        $json = [
            'User' => [
                'id' => 1
            ]
        ];
        $parse = new Parse($json, $this->method, '');
        $result = $parse->handle();

        $this->assertSame([
            'User' => [
                'id' => 1,
                'username' => 'abigeater',
                'email' => 'abigeater@163.com',
                'password' => '1',
                'create_time' => '2021-11-02 06:57:56'
            ]
        ], $result);
    }

    public function testWhereIn()
    {
        $json = [
            'User[]' => [
                'id{}' => [1,2]
            ]
        ];
        $parse = new Parse($json, $this->method, '');
        $result = $parse->handle();

        $this->assertSame([
            'User[]' => [
                [
                    'id' => 1,
                    'username' => 'abigeater',
                    'email' => 'abigeater@163.com',
                    'password' => '1',
                    'create_time' => '2021-11-02 06:57:56'
                ]
            ]
        ], $result);
    }

    public function testWhereQueryMany()
    {
        $json = [
            'User[]' => [
            ]
        ];
        $parse = new Parse($json, $this->method, '');
        $result = $parse->handle();

        $this->assertSame([
            'User[]' => [
                [
                    'id' => 1,
                    'username' => 'abigeater',
                    'email' => 'abigeater@163.com',
                    'password' => '1',
                    'create_time' => '2021-11-02 06:57:56'
                ]
            ]
        ], $result);
    }

    public function testWhereCondition()
    {
        $json = [
            'User' => [
                'id{}' => '>0,<=1'
            ]
        ];
        $parse = new Parse($json, $this->method, '');
        $result = $parse->handle();

        $this->assertSame([
            'User' => [
                'id' => 1,
                'username' => 'abigeater',
                'email' => 'abigeater@163.com',
                'password' => '1',
                'create_time' => '2021-11-02 06:57:56'
            ]
        ], $result);

        $json = [
            'User' => [
                'id{}' => '>1,<1'
            ]
        ];
        $parse = new Parse($json, $this->method, '');
        $result = $parse->handle();

        $this->assertSame([
            'User' => []
        ], $result);
    }

    public function testWhereArray()
    {
        $json = [
            '[]' => [
                'count'=> 10,
                'page' => 1,
                'User' => [
                    'username' => 'abigeater'
                ]
            ]
        ];
        $parse = new Parse($json, $this->method, '');
        $result = $parse->handle();

        $this->assertSame([
            '[]' => [
                 [
                     'User' => [
                         'id' => 1,
                         'username' => 'abigeater',
                         'email' => 'abigeater@163.com',
                         'password' => '1',
                         'create_time' => '2021-11-02 06:57:56'
                     ]
                ]
            ]
        ], $result);
    }

    public function testQueryManyTable()
    {
        $json = [
            'User' => [
                'username' => 'abigeater'
            ],
            'Message' => [
            ],
        ];
        $parse = new Parse($json, $this->method, '');
        $result = $parse->handle();

        $this->assertSame([
            'User' => [
                'id' => 1,
                'username' => 'abigeater',
                'email' => 'abigeater@163.com',
                'password' => '1',
                'create_time' => '2021-11-02 06:57:56'
            ],
            'Message' => [
                'id' => 12,
                'user_id' => 1,
                'watched_message_id' => '2'
            ]
        ], $result);
    }

    public function testQueryOneOne()
    {
        $json = [
            'Message' => [
            ],
            'User' => [
                'id@' => 'Message/user_id'
            ],
        ];
        $parse = new Parse($json, $this->method, '');
        $result = $parse->handle();

        $this->assertSame([
            'Message' => [
                'id' => 12,
                'user_id' => 1,
                'watched_message_id' => '2'
            ],
            'User' => [
                'id' => 1,
                'username' => 'abigeater',
                'email' => 'abigeater@163.com',
                'password' => '1',
                'create_time' => '2021-11-02 06:57:56'
            ]
        ], $result);
    }

    public function testQueryOneMany()
    {
        $json = [
            'User' => [
                'id' => 1
            ],
            '[]' => [
                'Message' => [
                    "user_id@" => "User/id"
                ],
            ]
        ];
        $parse = new Parse($json, $this->method, '');
        $result = $parse->handle();

        $this->assertSame([
            'User' => [
                'id' => 1,
                'username' => 'abigeater',
                'email' => 'abigeater@163.com',
                'password' => '1',
                'create_time' => '2021-11-02 06:57:56'
            ],
            '[]' => [
                [
                    'Message' => [
                        'id' => 12,
                        'user_id' => 1,
                        'watched_message_id' => '2'
                    ],
                ]
            ]
        ], $result);
    }

    public function testQueryArrayOneOne()
    {
        $json = [
            '[]' => [
                'Message' => [],
                'User' => [
                    "id@" => "[]/Message/user_id"
                ],
            ]
        ];
        $parse = new Parse($json, $this->method, '');
        $result = $parse->handle();

        $this->assertSame([
            '[]' => [
                [
                    'Message' => [
                        'id' => 12,
                        'user_id' => 1,
                        'watched_message_id' => '2'
                    ],
                    'User' => [
                        'id' => 1,
                        'username' => 'abigeater',
                        'email' => 'abigeater@163.com',
                        'password' => '1',
                        'create_time' => '2021-11-02 06:57:56'
                    ]
                ]
            ]
        ], $result);
    }
}
