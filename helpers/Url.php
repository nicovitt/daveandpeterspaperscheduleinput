<?php

namespace VittITServices\humhub\modules\daveandpeterspaperscheduleinput\helpers;


/**
 * Class Url
 */
class Url extends \yii\helpers\Url
{
    const ROUTE_SPACE = '/daveandpeterspaperscheduleinput/space/index';
    const ROUTE_SUBMIT_PAPER = '/daveandpeterspaperscheduleinput/space/index';
    const ROUTE_ADMIN = '/daveandpeterspaperscheduleinput/admin';

    function domainname()
    {
        return Url::base(true);
    }

    public static function toSpaceFromMenu()
    {
        return static::ROUTE_SPACE;
    }

    public static function toSpace()
    {
        return static::to([static::ROUTE_SPACE]);
    }

    public static function toSpaceWithMessage(string $baseurl, string $message)
    {
        return static::to([$baseurl . static::ROUTE_SPACE, 'message' => $message]);
    }

    public static function toSubmitPaper(string $baseurl)
    {
        return static::to([$baseurl . static::ROUTE_SUBMIT_PAPER]);
    }

    public static function toAdmin()
    {
        return static::to([static::ROUTE_ADMIN]);
    }
}
