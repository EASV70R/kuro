<?php
defined('BASE_PATH') or exit('No direct script access allowed');

class Session
{
    public static function Init(): void
    {
        session_start();

        if (!isset($_SESSION['ipaddress']) && !isset($_SESSION['useragent']) && !isset($_SESSION['lastaccess'])) {
            Session::Set("ipaddress", $_SERVER['REMOTE_ADDR']);
            Session::Set("useragent", $_SERVER['HTTP_USER_AGENT']);
            Session::Set("lastaccess", time());
        }
    }

    public static function Get(string $key)
    {
        return (isset($_SESSION[$key])) ? $_SESSION[$key] : false;
    }

    public static function CreateUserSession($user)
    {
        Session::Set("login", true);
        Session::Set("uid", (int) $user->userId);
        Session::Set("username", (string) $user->username);
        Session::Set("email", (string) $user->email);
        Session::Set("role", (string)Util::GetRoleName((int)$user->roleId));
        Session::Set("roleId", (int)$user->roleId);
        Session::Set("orgId", (int)$user->orgId);
        Session::Set("status", (int)$user->status);
        Session::Set("createdAt", (string)$user->createdAt);

        Session::Set("isSuperAdmin", Session::isSuperAdmin((int)$user->roleId));
        Session::Set("isOrgAdmin", Session::isOrgAdmin((int)$user->roleId));
        Session::Set("isRegularUser", Session::isRegularUser((int)$user->roleId));
    }

    public static function ValidateSession($auth): bool
    {
        if (!Session::Get("login")) {
            return false;
        }

        $userId = Session::Get("uid");
        $user = $auth->GetUserById($userId);
        if (!$user) {
            return false; // User does not exist
        }

        if ($user->roleId != Session::Get("roleId") || $user->status != Session::Get("status")) {
            Session::terminate();
            return false;
        }

        return true;
    }

    public static function terminate(): void
    {
        session_unset();
        session_destroy();
    }

    public static function Set(string $key, mixed $val): void
    {
        $_SESSION[$key] = $val;
    }

    public static function isSuperAdmin(int $roleId): bool
    {
        return $roleId === 1;
    }

    public static function isOrgAdmin(int $roleId): bool
    {
        return $roleId === 2;
    }

    public static function isRegularUser(int $roleId): bool
    {
        return $roleId === 3;
    }
}