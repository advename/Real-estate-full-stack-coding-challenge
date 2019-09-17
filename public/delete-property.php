<?php

if (empty($_GET['id'])) {
    header("refresh:5;url=account.php");
    echo "Invalid id. Cannot delete property.";
    exit;
}


session_start();
//Session does not exist, redirect to login/signup page
if (!$_SESSION) {
    header('Location: access.php');
    exit;
}
//Even if a session exists, check if a logged in session exists
//Session can also be used to store shopping cart values etc
//Therefore we have to check specifically that the userID session, which we defined, exists
if (empty($_SESSION['userID'])) {
    header('Location: access.php');
    exit;
}


$userID = $_SESSION['userID'];
$propertyID = $_GET['id'];
$jPropertiesData = json_decode(file_get_contents(__DIR__ . '/../data/properties.json'));
$jUsersData = json_decode(file_get_contents(__DIR__ . '/../data/users.json'));


//Check if property exists
if (!isset($jPropertiesData->$propertyID)) {

    header("refresh:5;url=account.php");
    echo "Invalid id. Cannot delete property.";
    exit;
}

//Check if the agent is allowed to delete the property
if (!$userID ==  $jPropertiesData->$propertyID->ownerAgentID) {

    header("refresh:5;url=account.php");
    echo "You are not the owner of this property";
    exit;
}



//remove from properties db
unset($jPropertiesData->$propertyID);
file_put_contents(__DIR__ . '/../data/properties.json', json_encode($jPropertiesData));

// remove from properties array of each user
if (($key = array_search($propertyID, $jUsersData->agents->$userID->properties)) !== false) {
    unset($jUsersData->agents->$userID->properties[$key]);
}
file_put_contents(__DIR__ . '/../data/users.json', json_encode($jUsersData));


header('Location: account.php');
