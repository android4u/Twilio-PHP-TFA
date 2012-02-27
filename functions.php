<?php
session_start();

include 'Services/Twilio.php';

/*
 * This file simply includes our Account SID, Auth Token, and a Twilio phone
 * number denoted below as $fromNumber.
 */
include 'credentials.php';

/*
 * This function takes a username and a preferred contact method, generates a
 *   new password, and sends it to the user via their preferred contact method.
 *
 * This is the most complicated of all the functions because it has so many
 *   steps. If you look at each piece individually, none are complicated.
 */
function user_generate_token($username, $phoneNum, $method){
    global $accountsid, $authtoken, $fromNumber;

    // Create a new password
    $password = substr(md5(time().rand(0, 10^10)), 0, 10);
//TODO: store in session
    $_SESSION['username'] = $username;
    $_SESSION['password'] = $password;

    $client = new Services_Twilio($accountsid, $authtoken);
    // Prepare the message with the password embedded
    $content = ('sms' == $method) ? "Your newly generated password is ".$password :
        "http://twimlets.com/message?Message%5B0%5D=Your%20newly%20generated%20password%20is%20%2C%2C" .
        urlencode(preg_replace("/(.)/i", "\${1},,", $password)) .
        "%20To%20repeat%20that%2C%20your%20password%20is%20%2C%2C" . urlencode(preg_replace("/(.)/i", "\${1},,", $password));
    $method  = ('sms' == $method) ? 'sms_messages' : 'calls';

    // Send the message via SMS or Voice
    $item = $client->account->$method->create(
                $fromNumber,    // The Twilio number we're sending from
                $phoneNum,      // The user's phone number
                $content
            );
    $message = "A new password has been generated and sent to your phone number.";

    return $message;
}

function user_login($username, $submitted) {

    $stored = $_SESSION['password'];

    if ($stored == $submitted) {
        $message = "Hello and welcome back $username";
    } else {
        $message = "Sorry, that's an invalid username and password combination.";
    }

    unset($_SESSION['username']);
    unset($_SESSION['password']);
    return $message;
}