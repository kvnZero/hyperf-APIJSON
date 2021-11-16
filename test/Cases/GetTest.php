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

    public function testWhereExist()
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

}
