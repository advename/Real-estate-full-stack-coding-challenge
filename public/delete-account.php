<?php


session_start();

//Session does not exist, redirect to login/signup page
if (!$_SESSION) {
    header('Location: access.php');
}
//Even if a session exists, check if a logged in session exists
//Session can also be used to store shopping cart values etc
//Therefore we have to check specifically that the userID session, which we defined, exists
if (empty($_SESSION['userID'])) {
    header('Location: access.php');
}

$jUsersData = json_decode(file_get_contents(__DIR__ . '/../data/users.json'));
$jPropertiesData = json_decode(file_get_contents(__DIR__ . '/../data/properties.json'));
$jCurrentUserData;

foreach ($jUsersData as $userTypes) {
    foreach ($userTypes as $id => $user) {
        if ($id == $_SESSION['userID']) {
            $jCurrentUserData = $user;
        }
    }
}

$currentUserType = $jCurrentUserData->userType;
$currentUserID = $_SESSION['userID'];

//Delete all properties linked to user
if ($currentUserType == 'agents') {
    foreach ($jCurrentUserData->properties as $id) {
        unset($jPropertiesData->$id);
    }
}
file_put_contents(__DIR__ . '/../data/properties.json', json_encode($jPropertiesData));



//Delete user
unset($jUsersData->$currentUserType->$currentUserID);
file_put_contents(__DIR__ . '/../data/users.json', json_encode($jUsersData));

session_destroy();

header("refresh:5;url=index.php");
echo "Account successfully deleted.";
