<?php
require_once 'vendor/autoload.php';
session_start();

$client = new Google_Client();
$client->setAuthConfig('credentials.json');
$client->setRedirectUri('http://localhost/google-calendar/callback.php');
$client->addScope(Google_Service_Calendar::CALENDAR);

if (!empty($_SESSION['access_token'])) {
    $client->setAccessToken($_SESSION['access_token']);

    if ($client->isAccessTokenExpired()) {
        unset($_SESSION['access_token']);
        header('Location: index.php');
        exit();
    }

    $service = new Google_Service_Calendar($client);
    $eventId = $_GET['eventId'];
    $service->events->delete('primary', $eventId);
    header('Location: index.php');
    exit();
} else {
    header('Location: index.php');
    exit();
}
?>
