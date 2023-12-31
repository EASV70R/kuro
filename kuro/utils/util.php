<?php
defined('BASE_PATH') or exit('No direct script access allowed');

class Util
{
    public static function Header(): void
    {
        include(SITE_ROOT.'/views/includes/header.inc.php');
    }

    public static function Navbar(): void
    {
        include(SITE_ROOT.'/views/includes/navbar.inc.php');
    }

    public static function Footer(): void
    {
        if (strpos($_SERVER['REQUEST_URI'], 'admin')) {
            include(SITE_ROOT.'/views/includes/footer.admin.inc.php');
        } else {
            include(SITE_ROOT.'/views/includes/footer.inc.php');
        }
    }

    public static function IsLoggedIn(): void
    {
        if (Session::Get('login')) {
            if (basename($_SERVER['REQUEST_URI']) == 'login' || basename($_SERVER['REQUEST_URI']) == 'register') {
                self::Redirect('/');
            }
        } else {
            if (basename($_SERVER['REQUEST_URI']) != 'login' && basename($_SERVER['REQUEST_URI']) != 'register') {
                self::Redirect('/');
            }
        }
    }

    public static function IsAdmin($auth): void
    {
        if (Session::Get('login')) {
            if (Session::Get('isSuperAdmin') || Session::Get('isOrgAdmin')) {
                if(Session::ValidateSession($auth)) {
                    return;
                } else {
                    self::Redirect('/');
                }
            } else {
                self::Redirect('/');
            }
        } else {
            self::Redirect('/');
        }
    }

    public static function GetRoleName(int $roleId): string
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

    public static function IsFrontPage(): bool
    {
        if (basename($_SERVER['REQUEST_URI']) != '' && basename($_SERVER['REQUEST_URI']) != 'home') {
            return false;
        } else {
            return true;
        }
    }

    public static function ConvertDate(string $date): string
    {
        return date('Y-m-d', strtotime($date));
    }

    public static function Refresh(): void
    {
        exit(header("Refresh:0"));
    }

    public static function Redirect(string $location): void
    {
        exit(header("Location: ${location}"));
    }

    public static function Print(string $string): string
    {
        return htmlspecialchars($string);
    }
}