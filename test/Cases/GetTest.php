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
 * @link:https://github.com/kvnZero/hyperf-APIJSON/issues/6
 */
class GetTest extends TestCase
{
    protected string $method = 'GET';

    public function testWhereExists()
    {
        $json = [
            "[]" => [
                "User" => [
                    "id}{@" => [
                        "from" =>"Comment",
                        "Comment" => [
                            "momentId" =>15,
                            "@column" =>"userId"
                        ]
                    ],
                    "@column" =>"id,sex,name"
                ]
            ]
        ];
        $parse = new Parse($json, $this->method, '');
        $result = $parse->handle();

        $this->assertSame([
            "[]" =>[
                [
                    "User" => [
                        "id" =>38710,
                        "sex" =>0,
                        "name" =>"TommyLemon"
                    ]
                ],
                [
                    "User" => [
                        "id" =>70793,
                        "sex" =>0,
                        "name" =>"Strong"
                    ]
                ],
                [
                    "User" => [
                        "id" =>82001,
                        "sex" =>0,
                        "name" =>"测试账号"
                    ]
                ],
                [
                    "User" => [
                        "id" =>82002,
                        "sex" =>1,
                        "name" =>"Happy~"
                    ]
                ],
                [
                    "User" => [
                        "id" =>82003,
                        "sex" =>0,
                        "name" =>"Wechat"
                    ]
                ],
                [
                    "User" => [
                        "id" =>82004,
                        "sex" =>0,
                        "name" =>"Tommy"
                    ]
                ],
                [
                    "User" => [
                        "id" =>82005,
                        "sex" =>1,
                        "name" =>"Jan"
                    ]
                ],
                [
                    "User" => [
                        "id" =>82006,
                        "sex" =>1,
                        "name" =>"Meria"
                    ]
                ],
                [
                    "User" => [
                        "id" =>82009,
                        "sex" =>0,
                        "name" =>"God"
                    ]
                ],
                [
                    "User" => [
                        "id" =>82012,
                        "sex" =>0,
                        "name" =>"Steve"
                    ]
                ]
            ]
        ], $result);
    }

    public function testWhereIn()
    {
        $json = [
            "[]" => [
                "User" => [
                    "id{}@" => [
                        "from" =>"Comment",
                        "Comment" => [
                            "momentId" =>15,
                            "@column" =>"userId"
                        ]
                    ],
                    "@column" =>"id,sex,name"
                ]
            ]
        ];
        $parse = new Parse($json, $this->method, '');
        $result = $parse->handle();

        $this->assertSame([
            "[]" =>[
                [
                    "User" => [
                        "id" =>38710,
                        "sex" =>0,
                        "name" =>"TommyLemon"
                    ]
                ],
                [
                    "User" => [
                        "id" =>82001,
                        "sex" =>0,
                        "name" =>"测试账号"
                    ]
                ],
                [
                    "User" => [
                        "id" =>82002,
                        "sex" =>1,
                        "name" =>"Happy~"
                    ]
                ],
                [
                    "User" => [
                        "id" =>82003,
                        "sex" =>0,
                        "name" =>"Wechat"
                    ]
                ],
                [
                    "User" => [
                        "id" =>82055,
                        "sex" =>1,
                        "name" =>"Solid"
                    ]
                ]
            ]
        ], $result);
    }

    public function testWhereInUseSubQuery()
    {
        $json = [
            "subquery@" => [
                "from" =>"Comment",
                "Comment" => [
                    "momentId" =>15,
                    "@column" =>"userId"
                ]
            ],
            "User[]" => [
                "User" => [
                    "id{}@" =>"subquery",
                    "@column" =>"id,sex,name"
                ]
            ],
            "[]" =>null
        ];
        $parse = new Parse($json, $this->method, '');
        $result = $parse->handle();

        $this->assertSame([
            "User[]" =>[
                [
                    "id" =>38710,
                    "sex" =>0,
                    "name" =>"TommyLemon"
                ],
                [
                    "id" =>82001,
                    "sex" =>0,
                    "name" =>"测试账号"
                ],
                [
                    "id" =>82002,
                    "sex" =>1,
                    "name" =>"Happy~"
                ],
                [
                    "id" =>82003,
                    "sex" =>0,
                    "name" =>"Wechat"
                ],
                [
                    "id" =>82055,
                    "sex" =>1,
                    "name" =>"Solid"
                ]
            ]
        ], $result);
    }

    public function testWhereExistsUseSubQuery()
    {
        $json = [
            "subquery@" => [
                "from" =>"Comment",
                "Comment" => [
                    "momentId" =>15,
                    "@column" =>"userId"
                ]
            ],
            "[]" => [
                "User" => [
                    "id}{@" =>"subquery",
                    "@column" =>"id,sex,name"
                ]
            ]
        ];
        $parse = new Parse($json, $this->method, '');
        $result = $parse->handle();

        $this->assertSame([
            "[]" =>[
                [
                    "User" => [
                        "id" =>38710,
                        "sex" =>0,
                        "name" =>"TommyLemon"
                    ]
                ],
                [
                    "User" => [
                        "id" =>70793,
                        "sex" =>0,
                        "name" =>"Strong"
                    ]
                ],
                [
                    "User" => [
                        "id" =>82001,
                        "sex" =>0,
                        "name" =>"测试账号"
                    ]
                ],
                [
                    "User" => [
                        "id" =>82002,
                        "sex" =>1,
                        "name" =>"Happy~"
                    ]
                ],
                [
                    "User" => [
                        "id" =>82003,
                        "sex" =>0,
                        "name" =>"Wechat"
                    ]
                ],
                [
                    "User" => [
                        "id" =>82004,
                        "sex" =>0,
                        "name" =>"Tommy"
                    ]
                ],
                [
                    "User" => [
                        "id" =>82005,
                        "sex" =>1,
                        "name" =>"Jan"
                    ]
                ],
                [
                    "User" => [
                        "id" =>82006,
                        "sex" =>1,
                        "name" =>"Meria"
                    ]
                ],
                [
                    "User" => [
                        "id" =>82009,
                        "sex" =>0,
                        "name" =>"God"
                    ]
                ],
                [
                    "User" => [
                        "id" =>82012,
                        "sex" =>0,
                        "name" =>"Steve"
                    ]
                ]
            ],
        ], $result);
    }

    public function testWhereIdSubQuery()
    {
        $json = [
            "User" => [
                "id@" => [
                    "from" =>"Comment",
                    "Comment" => [
                        "@column" =>"min(userId)"
                    ]
                ]
            ]
        ];
        $parse = new Parse($json, $this->method, '');
        $result = $parse->handle();

        $this->assertSame([
            "User" => [
                "id" =>38710,
                "sex" =>0,
                "name" =>"TommyLemon",
                "tag" =>"Android&Java",
                "head" =>"http://static.oschina.net/uploads/user/1218/2437072_100.jpg?t=1461076033000",
                "contactIdList" =>[
                    82003,
                    82005,
                    90814,
                    82004,
                    82009,
                    82002,
                    82044,
                    93793,
                    70793
                ],
                "pictureList" =>[
                    "http://static.oschina.net/uploads/user/1218/2437072_100.jpg?t=1461076033000",
                    "http://common.cnblogs.com/images/icon_weibo_24.png"
                ],
                "date" =>"2017-02-01 19:21:50"
            ]
        ], $result);
    }
}
