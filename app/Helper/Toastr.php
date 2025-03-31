<?php

namespace App\Helper;

class Toastr
{
    public static function showToastr()
    {
        return toastr()
            ->closeButton()
            ->preventDuplicates(true)
            ->positionClass('toast-top-center')
            ->newestOnTop(true);
    }

    public static function success($message = null, $title = null)
    {
        return self::showToastr()->addSuccess($message, $title);
    }

    public static function error($message = null, $title = null)
    {
        return self::showToastr()->addError($message, $title);
    }

    public static function warning($message = null, $title = null)
    {
        return self::showToastr()->addWarning($message, $title);
    }

    public static function info($message = null, $title = null)
    {
        return self::showToastr()->addInfo($message, $title);
    }
}
