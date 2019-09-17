<?php


if (empty($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    sendErrorResponse('Email is missing/invalid');
}
if (empty($_POST['password'])) {
    sendErrorResponse('Password is missing');
}

$jUsersData = json_decode(file_get_contents(__DIR__ . '/../../data/users.json'));

foreach ($jUsersData as $userTypes) {
    foreach ($userTypes as $user) {
        if ($_POST['email'] == $user->email) {

            //Email found, check if password matches the encrypted password
            $password = $user->password;
            if (password_verify($_POST['password'], $password)) {

                //All good, only verify if account is active
                if ($user->activated) {
                    //Start login session
                    session_start();
                    $_SESSION['userID'] = $user->id;

                    //Send success
                    sendSuccessResponse((object) [
                        "message" => "Success, user successfully logged in"
                    ]);
                } else {
                    sendErrorResponse('Account not active');
                }


                exit;
            } else {
                //User/password combination do not match, send error
                sendErrorResponse('Invalid credentials', 422);
            }
        } else {
            //continue searching for email
        }
    }
}

// Email has not been found, send erro
sendErrorResponse('Invalid credentials', 422);




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
