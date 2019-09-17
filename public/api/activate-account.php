<?php

// $wholeWebPath = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
// $currentWebDirectory = substr($wholeWebPath, 0, strrpos($wholeWebPath, '/') + 1);
// $accountPage = substr($currentWebDirectory, 0, strrpos($currentWebDirectory, '/') + 1);
// echo $accountPage;

//Check for empty get request
if (empty($_GET['id'])) {
    echo 'Wrong activation credentials';
    exit;
}
if (empty($_GET['key'])) {
    echo 'Wrong activation credentials';
    exit;
}
if (empty($_GET['type'])) {
    echo 'Wrong activation credentials';
    exit;
}
$sUserId = $_GET['id'];
$sActivationKey = $_GET['key'];
$sUserType = $_GET['type'];

$jUsersData = json_decode(file_get_contents(__DIR__ . '/../../data/users.json'));

//Check if user is already verified
if ($jUsersData->$sUserType->$sUserId->activated == true) {
    echo 'You cannot activate this account again';
    exit;
}

//Check if correct ID/Key combination
if ($jUsersData->$sUserType->$sUserId->activationKey == $sActivationKey) {
    //Activation key true, user authenticated
    $jUsersData->$sUserType->$sUserId->activated = true;
    file_put_contents(__DIR__ . '/../../data/users.json', json_encode($jUsersData));

    //Login user
    session_start();
    $_SESSION['userID'] = $sUserId;

    //redirect to account page

    header("refresh:5;url=../account.php?activated=true");
    echo '<h2>Success</h2>';
} else {
    //Wrong activation key, user not authenticated
    echo 'Wrong activation credentials 5';
}
