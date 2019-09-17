<?php

// echo json_encode($_POST);

if (empty($_POST['email'])) {
    sendErrorResponse('Email is missing');
}

$emailToCheck = $_POST['email'];

$jData = json_decode(file_get_contents(__DIR__ . '/../../data/users.json'));

foreach ($jData as $userTypes) {
    foreach ($userTypes as $user) {
        if ($emailToCheck == $user->email) {
            //email exsits, send error
            sendErrorResponse('Email already exists', 303);
        } else {
            //email new, send success
            sendSuccessResponse((object) [
                "message" => "User doesn't exist"
            ]);
        }
    }
}



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
