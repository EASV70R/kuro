<?php
header("Content-Type: application/json; charset=UTF-8");

require_once '../kuro/require.php';
require_once '../kuro/controllers/api.php';
require_once '../kuro/controllers/logs.php';

$api = new Api;
$log = new Logs;

if ($_SERVER["REQUEST_METHOD"] == "GET")
{
    if (empty($_GET['key'] || empty($_GET['user']) || empty($_GET['pass'])))
    {
        $response = array('status' => 'failed', 'error' => 'Missing arguments');
    }

    $apiKey = $_GET["key"];
    $username = $_GET["user"];
    $password = $_GET["pass"];
    
    $response = $api->UserLogin($apiKey, $username, $password);

    if($response['status'] === 'success')
    {
        $log->AddLog($response['data']->userId, $response['data']->orgId, 1, $_GET['key']);
    } else {
        $response2 = $api->GetUserIdByUsername($_GET['user']);
        if ($response2 > 0) {
            $log->AddLog($response2['data']->userId, $response2['data']->orgId, 0, $_GET['key']);
        }
    }

    echo json_encode($response);
}