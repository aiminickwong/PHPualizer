<?php
namespace PHPualizer\Routes;


use Slim\Http\Request;
use Slim\Http\Response;

class Posts
{
    public static function createUser(Request $req, Response $res)
    {
        $post = $req->getParams();

        if(isset($post['email'])) {
            if(\PHPualizer\Account::createAccount($post['username'], $post['email'], $post['password'], $post['firstname'], $post['lastname'])) {
                $_SESSION['message'] = 'The specified account was created successfully, you may now log in';
            }
        } else {
            $_SESSION['message'] = 'Could not parse data, form was not sent properly';
        }

        $res->getBody()->write('<meta http-equiv="refresh" content="0;url=/">');
    }
    
    public static function loginUser(Request $req, Response $res)
    {
        $post = $req->getParams();
        
        if(isset($post['username'])) {
            if(\PHPualizer\Account::login($post['username'], $post['password'])) {
                $_SESSION['message'] = 'The specified account was logged in successfully';
            } else {
                $_SESSION['message'] = 'There was an error logging in, do you have an account?';
            }
        } else {
            $_SESSION['message'] = 'Could not parse data, form was not sent properly';
        }

        $res->getBody()->write('<meta http-equiv="refresh" content="0;url=/">');
    }
}