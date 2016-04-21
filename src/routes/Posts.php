<?php
namespace PHPualizer\Routes;


use Slim\Http\Request;
use Slim\Http\Response;

class Posts
{
    public static function createUser(Request $req, Response $res)
    {
        $post = $req->getParsedBody();

        if(isset($post['email'])) {
            
        } else {
            return false;
        }
    }
}