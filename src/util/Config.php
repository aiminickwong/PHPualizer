<?php
namespace PHPualizer\Util;


class Config
{
    private static $m_CfgArray;

    public static function getConfigData(): array
    {
        $cfg_file = dirname(dirname(__DIR__)) . '/config.json';
        
        if(isset(self::$m_CfgArray)) {
            return self::$m_CfgArray;
        } else {
            $f_stream = fopen($cfg_file, 'r') or die('Couldn\'t read config file');
            $cfg_string = fread($f_stream, filesize($cfg_file));
            fclose($f_stream);

            self::$m_CfgArray = json_decode($cfg_string, true);

            if(self::$m_CfgArray != null)
                return self::$m_CfgArray;
            else
                throw new \Exception('The config file either contains no keys, or has invalid JSON');
        }
    }
    
    public static function getVersion(): string
    {
        $v_file = dirname(dirname(__DIR__)) . '/VERSION';
        
        $f_stream = fopen($v_file, 'r') or die('Couldn\'t load VERSION file!');
        $version = fread($f_stream, filesize($v_file));
        fclose($f_stream);

        return $version;
    }
}