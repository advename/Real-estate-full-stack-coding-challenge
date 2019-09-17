<?php

if (empty($_POST['name'])) {
    sendErrorResponse('Name is missing');
}

if (empty($_POST['userID'])) {
    sendErrorResponse('Id is missing');
}

if (empty($_POST['userType'])) {
    sendErrorResponse('Type is missing');
}


$userType = $_POST['userType'];
$userID = $_POST['userID'];
$name = $_POST['name'];
$jUsersData = json_decode(file_get_contents(__DIR__ . '/../../data/users.json'));

if ($jUsersData->$userType->$userID->name == $name) {
    sendErrorResponse('You have to change at least one letter of your name');
}

$jUsersData->$userType->$userID->name = $name;

//Save new users
file_put_contents(__DIR__ . '/../../data/users.json', json_encode($jUsersData));


sendSuccessResponse((object) [
    "message" => "User details successfully updated"
]);

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
