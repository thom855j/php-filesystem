<?php

namespace PHP\Filesystem;

class File
{
    public static function inc($path)
    {
        include_once $path . '.php';
    }

    public static function req($path)
    {
        require_once $path . '.php';
    }
}
