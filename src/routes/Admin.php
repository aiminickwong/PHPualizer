<?php
namespace PHPualizer\Routes;


use Slim\Http\Request;
use Slim\Http\Response;

class Admin
{
    public static function GET(Request $req, Response $res)
    {
        $twig = new \Twig_Environment(new \Twig_Loader_Filesystem(dirname(__DIR__) . '/templates'), [
            'cache' => 'cache'
        ]);


        if(isset($_SESSION['account'])) {
            $res->getBody()->write($twig->render('admin.twig', [
                'account' => $_SESSION['account']
            ]));
        } else {
            $res->getBody()->write($twig->render('admin.twig'));
        }

        return $res;
    }
}