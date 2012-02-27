<?php

include 'functions.php';

/*
 * First we retrieve each of the relevant variables and remove any
 *   non-alphanumeric characters filter them to protect against things such
 *   as SQL Injection.
 */
$username     = $_POST['username'];
$username     = preg_replace("/[^A-Za-z0-9]/", "", $username);
$password     = $_POST['password'];
$password     = md5($password);
$phone_number = $_POST['phone_number'];
$phoneNum     = preg_replace("/[^A-Za-z0-9]/", "", $phoneNum);
$method       = $_POST['method'];

$action       = $_POST['action'];
switch ($action) {
    case 'create':
        $message = user_create($username, $password, $phone_number);
        break;
    case 'login':
        $message = user_login($username, $password);
        break;
    case 'reset':
        $message = user_reset($username, $method);
        break;
    default:
        echo 'do nothing';
}
header("Location: index.php?message=" . urlencode($message));