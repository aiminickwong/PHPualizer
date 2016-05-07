<?php
namespace PHPualizer\Util;


class File
{
    public static function createFile(string $filename, string $content)
    {
        $file = fopen($filename, 'w') or die('Could not write to file ' . $filename);
        fwrite($file, $content);
        fclose($file);
    }
}