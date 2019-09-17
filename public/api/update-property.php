<?php

session_start();


if (empty($_POST['propertyID'])) {
    sendErrorResponse('No property specified');
}
if (empty($_POST['name'])) {
    sendErrorResponse('Name is missing');
}
if (empty($_POST['description'])) {
    sendErrorResponse('Description is missing');
}
if (empty($_POST['price'])) {
    sendErrorResponse('Price is missing');
}
if (empty($_POST['bedrooms'])) {
    sendErrorResponse('Bedrooms is missing');
}
if (empty($_POST['type'])) {
    sendErrorResponse('Type is missing');
}
if (empty($_POST['saleRent'])) {
    sendErrorResponse('Sale/Rent is missing');
}
if (empty($_POST['streetname'])) {
    sendErrorResponse('Streetname is missing');
}
if (empty($_POST['city'])) {
    sendErrorResponse('City is missing');
}
if (empty($_POST['zip'])) {
    sendErrorResponse('Zip is missing');
}
if (empty($_POST['country'])) {
    sendErrorResponse('Password is missing');
}
if (empty($_POST['latitude'])) {
    sendErrorResponse('Password is missing');
}
if (empty($_POST['longitude'])) {
    sendErrorResponse('Password is missing');
}

//get user id
$userID = $_SESSION['userID'];

$jPropertiesData = json_decode(file_get_contents(__DIR__ . '/../../data/properties.json'));

$propertyID = $_POST['propertyID']; //genereate a new uid
$timestamp = $jPropertiesData->$propertyID->created;


//Check if image file exists
if ($_FILES) {
    if (!empty($_FILES['img'])) {
        //Sanitize image
        //Check if the file extension is jpg or png file
        $sImageExtension = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
        // convert to lowercase so we don't have to worry about case sensitivity
        $sImageExtension = strtolower($sImageExtension);
        $aAllowedExtensions = ['jpg', 'png', 'jpeg'];
        if (!in_array($sImageExtension, $aAllowedExtensions)) {
            sendErrorResponse('Not supported file extension');
        }

        //Check if the file type is allowed
        $sImageType = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $_FILES['img']['tmp_name']); //-> dont use $_FILES['imgAgent]['type'] as OSX devices can send different headers -> https://stackoverflow.com/a/38658536/3673659
        $aAllowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!in_array($sImageType, $aAllowedTypes)) {
            sendErrorResponse('The file seems to be corrupted');
        }

        //Check that the file is not too small or too big
        $iImageSize = $_FILES['img']['size']; //below 1MB = 1024KB = 
        if (!$iImageSize > 20 * 1024 && !$iImageSize < 1024 * 1024 * 2) {
            sendErrorResponse('The file is too big');
        }

        //Save the file with an unique id inside the images folder
        $sUniqueImageName = $propertyID . '.' . pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION); //create a unique name for the image to make sure the img name does not exist twice
        $sImagePath = __DIR__ . "/../assets/user-uploads/";
        move_uploaded_file($_FILES['img']['tmp_name'], $sImagePath . $sUniqueImageName);
    }
} else {
    $sUniqueImageName = $jPropertiesData->$propertyID->img;
}

$updatedProperty = (object) [
    "name" => $_POST['name'],
    "description" => $_POST['description'],
    "price" => $_POST['price'],
    "bedrooms" => $_POST['bedrooms'],
    "type" => $_POST['type'],
    "img" => $sUniqueImageName,
    "saleRent" => $_POST['saleRent'],
    "currency" => "$",
    "address" => (object) [
        "streetname" => $_POST['streetname'],
        "city" => $_POST['city'],
        "zip" => $_POST['zip'],
        "country" => $_POST['country'],
    ],
    "coordinates" => (object) [
        "latitude" => $_POST['latitude'],
        "longitude" => $_POST['longitude'],
    ],
    "created" => $timestamp,
    "ownerAgentID" => $userID,
    "id" => $propertyID,

];

$jPropertiesData->$propertyID = $updatedProperty;

file_put_contents(__DIR__ . '/../../data/properties.json', json_encode($jPropertiesData));


//Send success
sendSuccessResponse((object) [
    "message" => "Success, property successfully updated"
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
