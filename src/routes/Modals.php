<?php
namespace PHPualizer\Routes;


use Slim\Http\Request;
use Slim\Http\Response;

class Modals
{
    public static function GET(Request $req, Response $res)
    {
        $twig = new \Twig_Environment(new \Twig_Loader_Filesystem(dirname(__DIR__) . '/templates/modals'), [
            'cache' => 'cache'
        ]);

        $name = $req->getAttribute("name");

        $res->getBody()->write($twig->render("$name.twig"));

        return $res;
    }
}