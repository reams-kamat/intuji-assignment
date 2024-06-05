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

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Server-side validation to ensure dates are not in the past and end is after start
        $startDateTime = $_POST['start'];
        $endDateTime = $_POST['end'];
        $currentDateTime = date('Y-m-d\TH:i');

        if ($startDateTime <= $currentDateTime || $endDateTime <= $currentDateTime) {
            $_SESSION['error'] = 'Event dates and times must be after the current date and time.';
        } elseif ($endDateTime <= $startDateTime) {
            $_SESSION['error'] = 'End DateTime must be after Start DateTime.';
        } else {
            $startDateTimeFormatted = date('c', strtotime($startDateTime));
            $endDateTimeFormatted = date('c', strtotime($endDateTime));

            $event = new Google_Service_Calendar_Event([
                'summary' => $_POST['summary'],
                'start' => ['dateTime' => $startDateTimeFormatted, 'timeZone' => 'America/Los_Angeles'],
                'end' => ['dateTime' => $endDateTimeFormatted, 'timeZone' => 'America/Los_Angeles']
            ]);

            try {
                $service->events->insert('primary', $event);
                header('Location: index.php');
                exit();
            } catch (Google_Service_Exception $e) {
                $_SESSION['error'] = 'Error creating event: ' . $e->getMessage();
            }
        }
    }
} else {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Event</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <nav class="navbar navbar-expand-lg navbar-light bg-light mt-3">
        <a class="navbar-brand" href="#">Google Calendar Integration</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a href="index.php" class="btn btn-secondary">Back to Events</a>
                </li>
            </ul>
        </div>
    </nav>

    <h1 class="mt-5">Create Event</h1>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <form method="post" class="mt-3" onsubmit="return validateForm()">
        <div class="form-group">
            <label for="summary">Summary:</label>
            <input type="text" id="summary" name="summary" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="start">Start DateTime:</label>
            <input type="datetime-local" id="start" name="start" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="end">End DateTime:</label>
            <input type="datetime-local" id="end" name="end" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Create Event</button>
    </form>
</div>

<script>
    function validateForm() {
        var start = document.getElementById('start').value;
        var end = document.getElementById('end').value;
        var currentDateTime = new Date().toISOString().slice(0, 16);

        if (start <= currentDateTime || end <= currentDateTime) {
            // alert('Event dates and times must be after the current date and time.');
            // return false;
        }
        if (end <= start) {
            // alert('End DateTime must be after Start DateTime.');
            // return false;
        }
        return true;
    }
</script>

</body>
</html>
