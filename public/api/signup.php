<?php


if (empty($_POST['name'])) {
    sendErrorResponse('Name is missing');
}
if (empty($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    sendErrorResponse('Email is missing/invalid');
}
if (empty($_POST['password'])) {
    sendErrorResponse('Password is missing');
}
if (empty($_POST['rePassword'])) {
    sendErrorResponse('Password is missing');
}
if ($_POST['rePassword'] != $_POST['password']) {
    sendErrorResponse('Passwords do not match');
}
if (empty($_POST['userType'])) {
    sendErrorResponse('Usertype not selected');
}
// var_dump($_POST['userType']);
if ($_POST['userType'] != "agents" && $_POST['userType'] != "users") {
    sendErrorResponse('Usertype does not exist');
}



$jUsersData = json_decode(file_get_contents(__DIR__ . '/../../data/users.json'));

$userID = uniqid(); //genereate a new uid
$activationKey = uniqid(); //generate an activation key to be sent via mail
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // encrypt password
$sUserType = $_POST['userType'];
$timestamp = time();

if ($sUserType == 'users') {
    $jNewUser = (object) [
        "name" => $_POST['name'],
        "email" => $_POST['email'],
        "password" => $password,
        "activated" => false,
        "activationKey" => $activationKey,
        "userCreated" => $timestamp,
        "id" => $userID,
        "userType" => $sUserType
    ];
} else {
    $jNewUser = (object) [
        "name" => $_POST['name'],
        "email" => $_POST['email'],
        "password" => $password,
        "activated" => false,
        "activationKey" => $activationKey,
        "userCreated" => $timestamp,
        "id" => $userID,
        "userType" => $sUserType,
        "properties" => []
    ];
}


$jUsersData->$sUserType->$userID = $jNewUser;

//Save new users
file_put_contents(__DIR__ . '/../../data/users.json', json_encode($jUsersData));


//Send activation key and echo successfull message
require_once(__DIR__ . '/send-activation-mail.php');



/**
 * Error message response
 */
function sendErrorResponse($errorMessage = "error", $errorStatus = 0)
{
    //Instead of using __LINE__ to get the current line, the three following lines are returning the line the function is fired from
    $bt = debug_backtrace();
    $caller = array_shift($bt);
    $lineNumber = $caller['line'];

    echo '{"status": "error",
        "statusCode": "' . $errorStatus . '",
        "message": "' . $errorMessage . '",
       "line": ' . $lineNumber . '}';
    exit;
}

/**
 * Success message response
 */
function sendSuccessResponse($successData = null)
{
    $status = (object) [
        "status" => "success",
        "statusCode" => 200
    ];

    //If no successodata has been passed, return an empty class
    if ($successData == null) {
        $successData = new stdClass;
    }

    //merge the basic success status object together with the custom object to return
    $jResp = (object) array_merge((array) $successData, (array) $status);

    echo json_encode($jResp);

    exit;
}
