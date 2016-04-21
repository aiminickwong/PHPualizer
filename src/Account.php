<?php
namespace PHPualizer;


use PHPualizer\Database\Driver;

class Account
{
    public static function createAccount(string $username, string $email, string $password, string $firstname, string $lastname, int $admin = 0): bool
    {
        $db = new Driver;
        $db = $db->getDriver();
        $db->setTable('users');

        if (!empty($db->getDocuments(['username' => $username]))) {
            return false;
        } else {
            return $db->insertDocuments([
                'username' => $username,
                'email' => $email,
                'password' => password_hash("$email:$password:$lastname", \PASSWORD_BCRYPT),
                'firstname' => $firstname,
                'lastname' => $lastname,
                'admin' => $admin
            ]);
        }
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
        @session_destroy();
    }
}