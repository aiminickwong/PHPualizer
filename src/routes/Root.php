<?php
namespace PHPualizer\Routes;


use Slim\Http\Request;
use Slim\Http\Response;
use Twig_Environment;

class Root
{
    public static function GET(Request $req, Response $res)
    {
        $twig = new \Twig_Environment(new \Twig_Loader_Filesystem(dirname(__DIR__) . '/templates'), [
            'cache' => 'cache'
        ]);

        if(isset($_SESSION['message']) && $_SESSION['message'] != null) {
            $res->getBody()->write($twig->render('index.twig', ['message' => $_SESSION['message']]));

            // Reset variables in session to avoid showing messages twice
            $_SESSION['message'] = null;
        } else {
            $res->getBody()->write($twig->render('index.twig'));
        }

        return $res;
    }
}
