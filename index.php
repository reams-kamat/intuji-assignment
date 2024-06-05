<?php
require_once 'vendor/autoload.php';
session_start();

$client = new Google_Client();
$client->setAuthConfig('credentials.json');
$client->setRedirectUri('http://localhost/google-calendar/callback.php');
$client->addScope(Google_Service_Calendar::CALENDAR);

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $_SESSION['access_token'] = $token;
    header('Location: index.php');
    exit();
}

if (!empty($_SESSION['access_token'])) {
    $client->setAccessToken($_SESSION['access_token']);

    if ($client->isAccessTokenExpired()) {
        unset($_SESSION['access_token']);
        header('Location: index.php');
        exit();
    }

    $service = new Google_Service_Calendar($client);
    $calendarId = 'primary';
    $events = $service->events->listEvents($calendarId);
} else {
    $authUrl = $client->createAuthUrl();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Google Calendar Integration</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <nav class="navbar navbar-expand-lg navbar-light bg-light mt-3">
        <a class="navbar-brand" href="#">Google Calendar Integration</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <?php if (isset($authUrl)): ?>
                    <li class="nav-item">
                        <a class="btn btn-primary" href="<?php echo $authUrl; ?>">Connect to Google Calendar</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a href="create.php" class="btn btn-success mr-2">Create Event</a>
                    </li>
                    <li class="nav-item">
                        <a href="disconnect.php" class="btn btn-secondary">Disconnect</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <?php if (!isset($authUrl)): ?>
        <h2 class="mt-5">Events</h2>
        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th>Event</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($events->getItems() as $event): ?>
                    <?php
                        $startDateTime = $event->getStart()->getDateTime();
                        $endDateTime = $event->getEnd()->getDateTime();
                        $start = !empty($startDateTime) ? date('Y-m-d H:i:s', strtotime($startDateTime)) : 'All Day';
                        $end = !empty($endDateTime) ? date('Y-m-d H:i:s', strtotime($endDateTime)) : 'All Day';
                    ?>
                    <tr>
                        <td><?php echo $event->getSummary(); ?></td>
                        <td><?php echo $start; ?></td>
                        <td><?php echo $end; ?></td>
                        <td>
                            <a href="delete.php?eventId=<?php echo $event->getId(); ?>" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
</body>
</html>
