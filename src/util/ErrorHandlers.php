<?php
namespace PHPualizer\Util;


use Exception;

class ErrorHandlers
{
    public static function session($code, $msg, $file, $line)
    {
        throw new Exception("Error #$code in $file on line $line: $msg");
    }
}