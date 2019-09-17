<?php

$propertyID;
$errorMessage = false;
$property;
session_start();
if (empty($_GET['id'])) {
    $errorMessage = "No property has been specified";
} else {
    $propertyID = $_GET['id'];
    $jPropertiesData = json_decode(file_get_contents(__DIR__ . '/../data/properties.json'));

    if (isset($jPropertiesData->$propertyID)) {
        //id exists
        $property = $jPropertiesData->$propertyID;
    } else {
        //id does not exist
        $errorMessage = "Property does not exist";
    }
}






?>

<?php require_once('elements/header.php'); ?>
<!-- Import mapbox dependencies -->
<script src='https://api.mapbox.com/mapbox-gl-js/v1.3.1/mapbox-gl.js'></script>
<link href='https://api.mapbox.com/mapbox-gl-js/v1.3.1/mapbox-gl.css' rel='stylesheet' />


<!-- Start the HTML page -->
<main id="single-property">
    <div class="container">
        <div class="grid-box">
            <?php if ($errorMessage) : ?>
                <!-- Display error message -->
                <div>
                    <h3><?php echo $errorMessage; ?></h3>
                </div>

            <?php else : ?>
                <!-- Show property/page content -->
                <div class="left" style="background: url('assets/user-uploads/<?php echo $property->img ?>') no-repeat center center; background-size: cover;">

                </div>
                <div class="right">

                    <h3 class="name"><?php echo $property->name ?></h3>
                    <div class="top-highlight-info">
                        <h2 class="price"><?php echo $property->price ?> <?php echo $property->currency ?></h2>
                        <p class="bedrooms"><?php echo $property->bedrooms ?> bedrooms</p>
                        <p>|</p>
                        <p class="type"><?php echo $property->type ?> for <?php echo $property->saleRent ?></p>
                    </div>
                    <p class="address"><?php echo $property->address->streetname ?>, <?php echo $property->address->zip ?> <?php echo $property->address->city ?>, <?php echo $property->address->country ?></p>
                    <div class="description">
                        <p><?php echo $property->description ?></p>
                    </div>
                    <div id="property-map"></div>


                </div>
            <?php endif; ?>
        </div>

        <?php if ($_SESSION['userID'] == $property->ownerAgentID) : /* Display options if the current logged in user is the owner of this listing */ ?>
            <div class="options">
                <h4>You are the owner of this property listing.</h4>
                <div class="wrapper">
                    <a href="edit-property.php?id=<?php echo $propertyID; ?>" class="edit-button">Edit</a>
                    <a href="delete-property.php?id=<?php echo $propertyID; ?>" class="delete-button">Delete</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<!-- Scripts -->
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="scripts/single-property.js" type="text/javascript"></script>
<script>
    let marker = new mapboxgl.Marker()
        .setLngLat([<?php echo $property->coordinates->latitude ?>, <?php echo $property->coordinates->longitude ?>])
        .addTo(map);

    map.flyTo({
        center: [<?php echo $property->coordinates->latitude ?>, <?php echo $property->coordinates->longitude ?>],
        zoom: 14
    });
</script>
<?php require_once('elements/footer.php'); ?>