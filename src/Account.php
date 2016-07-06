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

        // Sanitize and lowercase variables
        $username = strtolower(filter_var($username, \FILTER_SANITIZE_STRING));
        $email = strtolower(filter_var($email, \FILTER_SANITIZE_EMAIL));
        $password = filter_var($password, \FILTER_SANITIZE_SPECIAL_CHARS);
        $firstname = filter_var($firstname, \FILTER_SANITIZE_STRING);
        $lastname = filter_var($lastname, \FILTER_SANITIZE_STRING);
        $admin = filter_var($admin, \FILTER_SANITIZE_NUMBER_INT);

        $checkusernameindb = $db->getDocuments(['username' => $username]);
        $checkemailindb = $db->getDocuments(['email' => $email]);

        if(isset($checkusernameindb[0]['username'])) {
            $_SESSION['message'] = 'An account already exists with the specified username';
            return false;
        } else {
            if(isset($checkemailindb[0]['email'])) {
                $_SESSION['message'] = 'An account already exists with the specified email';
                return false;
            } else {
                return $db->insertDocuments([
                    'username' => $username,
                    'email' => $email,
                    'password' => password_hash("$email:$password:$lastname", \PASSWORD_BCRYPT),
                    'first_name' => $firstname,
                    'last_name' => $lastname,
                    'admin' => $admin
                ]);
            }
        }
    }
    
    public static function deleteAccount(string $usernameoremail): bool
    {
        
    }

    public static function login(string $usernameoremail, string $password): bool
    {
        $usernameoremail = strtolower($usernameoremail);
        $password = filter_var($password, \FILTER_SANITIZE_SPECIAL_CHARS);

        $db = new Driver;
        $db = $db->getDriver();
        $db->setTable('users');

        if(strpos($usernameoremail, '@') === false) {
            $account = $db->getDocuments(['username' => filter_var($usernameoremail, \FILTER_SANITIZE_STRING)], 0);
            if(isset($account['email'])) {
                if(password_verify($account['email'] . ':' . $password . ':' . $account['last_name'], $account['password'])) {
                    // Don't flop around user's password hash in session
                    unset($account['password']);
                    $_SESSION['account'] = $account;

                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            $account = $db->getDocuments(['email' => filter_var($usernameoremail, \FILTER_SANITIZE_EMAIL)], 0);
            if(isset($account['email'])) {
                if(password_verify($account['email'] . ':' . $password . ':' . $account['last_name'], $account['password'])) {
                    unset($account['password']);
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
    
    public static function logout(): bool
    {
        if(isset($_SESSION['account'])) {
            unset($_SESSION['account']);
            
            return true;
        } else {
            return false;
        }
    }
}