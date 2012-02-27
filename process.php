<?php

include 'functions.php';

/*
 * First we retrieve each of the relevant variables and remove any
 *   non-alphanumeric characters filter them to protect against things such
 *   as SQL Injection.
 */
$username = isset($_POST['username']) ? $_POST['username'] : '';
$username = preg_replace("/[^A-Za-z0-9]/", "", $username);
$password = isset($_POST['password']) ? $_POST['password'] : '';
$password = preg_replace("/[^A-Za-z0-9]/", "", $password);
$phoneNum = isset($_POST['phone_number']) ? $_POST['phone_number'] : '';
$phoneNum = preg_replace("/[^0-9]/", "", $phoneNum);
$method   = isset($_POST['method']) ? $_POST['method'] : '';

$action   = isset($_POST['action']) ? $_POST['action'] : '';
switch ($action) {
    case 'token':
        $message = user_generate_token($username, $phoneNum, $method);
        break;
    case 'login':
        $message = user_login($username, $password);
        break;
    default:
        echo 'do nothing';
}
header("Location: index.php?message=" . urlencode($message));