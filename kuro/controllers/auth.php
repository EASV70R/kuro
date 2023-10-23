<?php
defined('BASE_PATH') or exit('No direct script access allowed');

require_once __DIR__.'/../models/user.model.php';
require_once __DIR__.'/../utils/session.php';
require_once __DIR__.'/../utils/validator.php';

class Auth
{
    public function GetAllUsers(): array
    {
        $User = new UserModel();
        return $User->GetUsers();
    }

    public function GetAllOrgs(): array
    {
        $User = new UserModel();
        return $User->GetAllOrgs();
    }

    public function GetRoleName(int $roleId): string
    {
        switch ($roleId) {
            case 1:
                return 'Super Admin';
            case 2:
                return 'Org Admin';
            case 3:
                return 'Regular User';
            default:
                return 'Unknown';
        }
    }

    public function GetOrgName(int $orgId): string
    {
        $orgs = $this->GetAllOrgs();
        foreach ($orgs as $org) {
            if ($org->orgId == $orgId) {
               
                return $org->orgName;
            }
        }
        return 'Unknown';
    }

    public function RegisterSuperAdmin($data): string
    {
        try{
            $User = new UserModel();

            $username = trim($data['username']);
            $password = (string) $data['password'];
            $confirmPassword = (string) $data['confirmPassword'];
            $email = (string) $data['email'];
            $roleId = (int) $data['roleId'];
            $orgId = (int) $data['orgId'];

            $userExists = $User->GetUsername($username);
            if ($userExists) {
                return "Username already exists.";
            }
  
            $emailExists = $User->GetEmail($email);
            if ($emailExists) {
                return "Email already exists.";
            }

            $validationError = Validator::RegisterFormAdm($username, $password, $confirmPassword, $email, $roleId, $orgId);
            if ($validationError) {
                return $validationError;
            }

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $response = $User->RegisterSuperAdmin($username, $hashedPassword, $email, $roleId, $orgId);
            if ($response) {
                return 'Registration successful.';
            } else {
                return 'Registration failed.';
            }
        } catch (Throwable $error) {
            return 'Registration failed.';
        } finally {
            
        }
    }

    public function Login($data): null|string
    {
        $User = new UserModel();

        $username = trim($data['username']);
        $password = (string) $data['password'];

        $validationError = Validator::LoginForm($username, $password);
        if ($validationError) {
            return $validationError;
        }

        $response = $User->Login($username, $password);
        if ($response) {
            Session::CreateUserSession($response);
            Util::Redirect('/admin/admin');
            return ($response) ? 'Login successful.' : 'Login failed.';
        } else {
            return 'Invalid username or password.';
        }
    }

    public function Logout()
    {
        session_unset();
        $_SESSION = array();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
    }
}
$auth = new Auth();
if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    if(isset($_POST['login']))
    {
        $response = $auth->Login($_POST);
    }  
    if(isset($_POST['registerSuperAdmin']))
    {
        $response = $auth->RegisterSuperAdmin($_POST);
        var_dump($response);
        var_dump($_POST);
    }
}