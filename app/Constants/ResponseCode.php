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
namespace App\Constants;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

/**
 * @Constants
 */
class ResponseCode extends AbstractConstants
{
    /**
     * @Message("success")
     * copy:https://github.com/Tencent/APIJSON/blob/master/APIJSONORM/src/main/java/apijson/JSONResponse.java#L40
     */
    public const CODE_SUCCESS = 200;

    /**
     * @Message("编码错误")
     */
	public const CODE_UNSUPPORTED_ENCODING = 400;

    /**
     * @Message("权限错误")
     */
	public const CODE_ILLEGAL_ACCESS = 401;

    /**
     * @Message("禁止操作")
     */
	public const CODE_UNSUPPORTED_OPERATION = 403;

    /**
     * @Message("未找到")
     */
	public const CODE_NOT_FOUND = 404;

    /**
     * @Message("参数错误")
     */
	public const CODE_ILLEGAL_ARGUMENT = 406;

    /**
     * @Message("未登录")
     */
	public const CODE_NOT_LOGGED_IN = 407;

    /**
     * @Message("超时")
     */
	public const CODE_TIME_OUT = 408;

    /**
     * @Message("重复，已存在")
     */
	public const CODE_CONFLICT = 409;

    /**
     * @Message("条件错误，如密码错误")
     */
	public const CODE_CONDITION_ERROR = 412;

    /**
     * @Message("类型错误")
     */
	public const CODE_UNSUPPORTED_TYPE = 415;

    /**
     * @Message("超出范围")
     */
	public const CODE_OUT_OF_RANGE = 416;

    /**
     * @Message("对象为空")
     */
	public const CODE_NULL_POINTER = 417;

    /**
     * @Message("服务器内部错误")
     */
	public const CODE_SERVER_ERROR = 500;
}
