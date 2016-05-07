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
            if(\PHPualizer\Account::createAccount($post['username'], $post['email'], $post['password'],
                $post['firstname'], $post['lastname'])) {
                $_SESSION['message'] = 'The specified account was created successfully, you may now log in';
            } else {
                $_SESSION['message'] = 'There was an error saving the account to the database, please try again later';
            }
        } else {
            $_SESSION['message'] = 'Could not parse data, form was not sent properly';
        }
    }
}