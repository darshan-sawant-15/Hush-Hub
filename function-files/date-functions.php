<?php function getDateInterval($date)
{
    $givenDate = new DateTime($date, new DateTimeZone('Asia/Kolkata'));
    $currentDate = new DateTime('now', new DateTimeZone('Asia/Kolkata'));

    $interval = $currentDate->diff($givenDate);

    $days = $interval->days;
    $hours = $interval->h;
    $minutes = $interval->i;
    $seconds = $interval->s;

    $output = '';

    if ($days === 0) {
        if ($hours === 0) {
            if ($minutes === 0) {
                $output = ($seconds > 0) ? "$seconds seconds ago" : "$seconds second ago";
            } else {
                $output = ($minutes > 0) ? "$minutes minute ago" : "$minutes minute ago";
            }
        } else {
            $output = ($hours > 0) ? "$hours hours ago" : "$hours hour ago";
        }
    } else {
        $output = ($days > 0) ? "$days days ago" : "$days day ago";
    }

    return $output;
}

function getDateForPost($datetime)
{
    $formattedDateTime = date("F j, Y, h:i A", strtotime($datetime));
    return $formattedDateTime;
}
?>