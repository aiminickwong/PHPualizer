<?php
namespace PHPualizer\Routes;


use Slim\Http\Request;
use Slim\Http\Response;

class Root
{
    public static function GET(Request $req, Response $res)
    {
        $config = \PHPualizer\Config::getConfigData();

        $res->getBody()->write(json_encode([
            'authRequired' => $config['authentication'],
            'version' => \PHPualizer\Config::getVersion()
        ]));

        return $res;
    }
}
