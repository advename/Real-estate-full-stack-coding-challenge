<?php

session_start();

if (empty($_GET['id'])) {

    header("refresh:5;url=account.php");
    echo "Invalid id. Cannot edit property.";
    exit;
}


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
$jUsersData = json_decode(file_get_contents(__DIR__ . '/../data/users.json'));
$jPropertiesData = json_decode(file_get_contents(__DIR__ . '/../data/properties.json'));
$jProperty = $jPropertiesData->$propertyID;

foreach ($jUsersData as $userTypes) {
    foreach ($userTypes as $id => $user) {
        if ($id == $_SESSION['userID']) {
            $jCurrentUserData = $user;
        }
    }
}

?>

<?php require_once('elements/header.php'); ?>
<!-- Import mapbox dependencies -->
<script src='https://api.mapbox.com/mapbox-gl-js/v1.3.1/mapbox-gl.js'></script>
<link href='https://api.mapbox.com/mapbox-gl-js/v1.3.1/mapbox-gl.css' rel='stylesheet' />


<!-- Start the HTML page -->
<main id="update-property">
    <div class="container">
        <h2 class="title">Edit property</h2>
        <div class="grid-container">
            <div class="grid-box">

                <!-- Top left -->
                <div class="top-left">
                    <h3>Basic details</h3>
                    <form>
                        <input type="hidden" class="property-id" name="propertyID" value="<?php echo $propertyID ?>">
                        <div class="form-content">
                            <p class="top">Name</p>
                            <input type="text" name="name" class="name" placeholder="Copenhagen Mansion" value="<?php echo $jProperty->name ?>">
                        </div>
                        <div class="form-content">
                            <p class="top">Description</p>
                            <textarea rows="4" cols="50" name="description" class="description" placeholder="Describe your property..."><?php echo $jProperty->description ?></textarea>
                        </div>
                        <div class="form-content">
                            <p class="top">Price</p>
                            <input type="number" name="price" class="price" placeholder="300.000" value="<?php echo $jProperty->price ?>">
                        </div>
                        <div class="bedrooms">
                            <div class="content">
                                <p class="top">Bedrooms</p>
                                <div class="slider">
                                    <input class="bedrooms-value" name="bedrooms" type="range" min="0" max="10" step="1" value="<?php echo $jProperty->bedrooms ?>">
                                    <div class="value"><?php echo $jProperty->bedrooms ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-content-dropdown property-type">
                            <div class="content">
                                <p class="top">Property type</p>
                                <div class="dropdown">
                                    <ul>
                                        <li>Apartment</li>
                                        <li>House</li>
                                        <li>Condos</li>
                                    </ul>
                                </div>
                                <div class="current-selection">
                                    <p><?php echo $jProperty->type ?></p>
                                    <span></span>
                                    <input type="hidden" name="type" value="<?php echo $jProperty->type ?>" class="property-type-value" />
                                </div>
                            </div>
                        </div>
                        <div class="form-content-dropdown sale-rent">
                            <div class="content">
                                <p class="top">Sale/Rent</p>
                                <div class="dropdown">
                                    <ul>
                                        <li>Sale</li>
                                        <li>Rent</li>
                                    </ul>
                                </div>
                                <div class="current-selection">
                                    <p><?php echo $jProperty->saleRent ?></p>
                                    <span></span>
                                    <input type="hidden" name="saleRent" value="<?php echo $jProperty->saleRent ?>" class="sale-rent-value" />
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Top top-right -->
                <div class="top-right">
                    <h3>Image</h3>
                    <form enctype="multipart/form-data">
                        <img src="assets/user-uploads/<?php echo $jProperty->img ?>" alt="" class="placeholder">
                        <div class="form-content">
                            <p class="top">Upload an image of the property</p>
                            <input type="file" name="img" class="img-file">
                        </div>
                    </form>

                </div>
                <!-- Bottom left -->
                <div class="bottom-left">
                    <h3 class="box-title">Address</h3>
                    <form>
                        <div class="form-content">
                            <p class="top">Streetname, Housenumber</p>
                            <input type="text" name="streetname" class="streetname" placeholder="Main str., 123" value="<?php echo $jProperty->address->streetname ?>">
                        </div>
                        <div class="form-content">
                            <p class="top">City</p>
                            <input type="text" name="city" class="city" placeholder="Copenhagen" value="<?php echo $jProperty->address->city ?>">
                        </div>
                        <div class="form-content">
                            <p class="top">ZIP Code</p>
                            <input type="text" name="zip" class="zip" placeholder="2000" value="<?php echo $jProperty->address->zip ?>">
                        </div>
                        <div class="form-content-dropdown country">
                            <div class="content">
                                <p class="top">Country</p>
                                <div class="dropdown">
                                    <ul>
                                        <li>Denmark</li>
                                        <li>Germany</li>
                                        <li>Norway</li>
                                        <li>Sweden</li>
                                    </ul>
                                </div>
                                <div class="current-selection">
                                    <p><?php echo $jProperty->address->country ?></p>
                                    <span></span>
                                    <input type="hidden" name="country" value="<?php echo $jProperty->address->country ?>" class="country-value" />
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="bottom-right">
                    <div id="update-property-map"></div>
                    <button id="update-map">Update map</button>
                    <p class="map-error-message"></p>
                </div>
            </div>
            <button id="update-property-btn">
                UPDATE PROPERTY
            </button>
            <p class="update-property-error-message"></p>
        </div>

    </div>

</main>

<!-- Scripts -->
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="scripts/update-property.js" type="text/javascript"></script>
<?php require_once('elements/footer.php'); ?>