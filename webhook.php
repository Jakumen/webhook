<?php
$payload = @file_get_contents('php://input');
$event = json_decode($payload);

if($event->data->attributes->status == 'paid') {
    // Handle payment success
    header("Location: success.html");
    exit();
} else {
    // Handle payment failure
    header("Location: failure.html");
    exit();
}
?>
