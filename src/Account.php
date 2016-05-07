<?php
namespace PHPualizer;


use PHPualizer\Database\Driver;

class Account
{
    public static function createAccount(string $username, string $email, string $password, string $firstname, string $lastname, int $admin = 0): bool
    {
        $db = new Driver;
        $db = $db->getDriver();
        $db->setTable('users_test');

//        die(var_dump($db->getDocuments(['username' => 'Wargog'])));

//        if(!empty($db->getDocuments(['username' => $username]))) {
//            return false;
//        } else {
            return $db->insertDocuments([
                'username' => filter_var($username, \FILTER_SANITIZE_STRING),
                'email' => filter_var($email, \FILTER_SANITIZE_EMAIL),
                'password' => password_hash("$email:$password:$lastname", \PASSWORD_BCRYPT),
                'firstname' => filter_var($firstname, \FILTER_SANITIZE_STRING),
                'lastname' => filter_var($lastname, \FILTER_SANITIZE_STRING),
                'admin' => filter_var($admin, \FILTER_SANITIZE_NUMBER_INT)
            ]);
//        }
    }

    public static function login(string $usernameoremail, string $password): bool
    {
        $db = new Driver;
        $db = $db->getDriver();
        $db->setTable('users');

        if(strpos($usernameoremail, '@') !== false) {
            $account = $db->getDocuments(['username' => $usernameoremail], 0);
            if(isset($account['username'])) {
                if(password_verify($account['email'] . ':' . $password . ':' . $account['lastname'], $account['password'])) {
                    $_SESSION['account'] = $account;
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            $account = $db->getDocuments(['email' => $usernameoremail], 0);
            if(isset($account['email'])) {
                if(password_verify($account['email'] . ':' . $password . ':' . $account['lastname'], $account['password'])) {
                    $_SESSION['account'] = $account;
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }
    
    public static function logout()
    {
        set_error_handler('\PHPualizer\Util\ErrorHandlers::session');
        session_destroy();
        restore_error_handler();
    }
}