<?php
namespace PHPualizer\Routes;


use Slim\Http\Request;
use Slim\Http\Response;

class Account
{
    public static function GET(Request $req, Response $res)
    {
        $jade = new \Jade\Jade([
            'prettyprint' => true,
            'cache' => 'cache/'
        ]);


        if(isset($_SESSION['account'])) {
            $res->getBody()->write($jade->render('src/templates/account.jade', [
                'account' => $_SESSION['account']
            ]));
        } else {
            $res->getBody()->write($jade->render('src/templates/account.jade'));
        }

        return $res;
    }
}