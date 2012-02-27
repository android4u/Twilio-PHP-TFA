<?php

include 'Services/Twilio.php';

/*
 * This file simply includes our Account SID, Auth Token, and a Twilio phone
 * number denoted below as $fromNumber.
 */
include 'credentials.php';

$dbConn = mysql_connect('localhost', 'mydemouser', 'mydemopass');
if (!$dbConn) {
    die('Could not connect to the database.');
}
mysql_select_db('mydemodb', $dbConn);

/*
 * This function checks the username/password combination against the database.
 */
function user_login($username, $password) {
    global $dbConn;

    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysql_query($query);
    if ($row = mysql_fetch_assoc($result)) {
        $message = "Hello and welcome back $username";
    } else {
        $message = "Sorry, that's an invalid username and password combination.";
    }

    return $message;
}

/*
 * This function creates a new user based on the provided username and password.
 *   Immediately after, it sends the new user a welcome SMS.
 */
function user_create($username, $password, $phone_number) {
    global $dbConn, $accountsid, $authtoken, $fromNumber;

    $query = "INSERT INTO users (username, password, phone_number) VALUES ('$username', '$password', '$phone_number')";
    mysql_query($query);

    $message = "A user by the name of $username was created successfully. Please feel free to log in.";

    $client = new Services_Twilio($accountsid, $authtoken);
    $item = $client->account->sms_messages->create(
                $fromNumber,      // The Twilio number we're sending from
                $phone_number,    // The user's phone number
                $message
            );
    return $message;
}

/*
 * This function takes a username and a preferred contact method, generates a
 *   new password, and sends it to the user via their preferred contact method.
 *
 * This is the most complicated of all the functions because it has so many
 *   steps. If you look at each piece individually, none are complicated.
 */
function user_reset($username, $method) {
    global $dbConn, $accountsid, $authtoken, $fromNumber;

    // Check to see if the user exists
    $query = "SELECT phone_number FROM users WHERE username = '$username'";
    $result = mysql_query($query);
    if ($row = mysql_fetch_assoc($result)) {
        // Create a new password
        $password = substr(md5(time().rand(0, 10^10)), 0, 10);
        // Update the database record with the new password
        $query = "UPDATE users SET password = '".md5($password)."' WHERE username = '$username'";
        mysql_query($query);

        $client = new Services_Twilio($accountsid, $authtoken);
        // Prepare the message with the password embedded
        $content = ('sms' == $method) ? "Your newly generated password is ".$password :
            "http://twimlets.com/message?Message%5B0%5D=Your%20newly%20generated%20password%20is%20%2C%2C" .
            urlencode(preg_replace("/(.)/i", "\${1},,", $password)) .
            "%20To%20repeat%20that%2C%20your%20password%20is%20%2C%2C" . urlencode(preg_replace("/(.)/i", "\${1},,", $password));
        $method  = ('sms' == $method) ? 'sms_messages' : 'calls';

        // Send the message via SMS or Voice
        $item = $client->account->$method->create(
                $fromNumber,            // The Twilio number we're sending from
                $row['phone_number'],   // The user's phone number
                $content
                );
        $message = "A new password has been generated and sent to your phone number on file.";
    } else {
        $message = "Sorry, that's an unknown username.";
    }

    return $message;
}