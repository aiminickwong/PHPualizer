<?php
namespace PHPualizer\Routes;


use Slim\Http\Request;
use Slim\Http\Response;

class Modals
{
    public static function GET(Request $req, Response $res)
    {
        $jade = new \Jade\Jade([
            'prettyprint' => true,
            'cache' => 'cache/'
        ]);

        $name = $req->getAttribute("name");

        $res->getBody()->write($jade->render("src/templates/modals/$name.jade"));

        return $res;
    }
}