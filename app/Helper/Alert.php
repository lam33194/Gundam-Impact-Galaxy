<?php

namespace App\Helper;


class Alert
{
    public static function showAlert()
    {
        return sweetalert()
            ->timer(3000)
            ->showCloseButton();
    }

    public static function success($message = null, $title = null)
    {
        return self::showAlert()->addSuccess($message, $title);
    }

    public static function error($message = null, $title = null)
    {
        return self::showAlert()->addError($message, $title);
    }
    public static function warning($message = null, $title = null)
    {
        return self::showAlert()->addWarning($message, $title);
    }
    public static function info($message = null, $title = null)
    {
        return self::showAlert()->addInfo($message, $title);
    }
}
