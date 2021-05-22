<?php

namespace Sarbas\Setting\Exceptions;

use InvalidArgumentException;

class SettingDoesNotExist extends InvalidArgumentException
{
    public static function withKey(string $key)
    {
        return new static("There is no setting with key `{$key}`.");
    }
}
