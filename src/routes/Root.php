<?php
namespace PHPualizer\Routes;


use Slim\Http\Request;
use Slim\Http\Response;

class Root
{
    public static function GET(Request $req, Response $res)
    {
        $jade = new \Jade\Jade([
            'prettyprint' => true,
            'cache' => 'cache/'
        ]);

        if($_SESSION['message'] != null) {
            $res->getBody()->write($jade->render('src/templates/index.jade', ['message' => $_SESSION['message']]));

            // Reset variables in session to avoid showing messages twice
            $_SESSION['message'] = null;
        } else {
            $res->getBody()->write($jade->render('src/templates/index.jade'));
        }

        return $res;
    }
}
