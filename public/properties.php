<?php

//Save queries inside variables

$queryCity = !empty($_GET['city']) ? $_GET['city'] : false;
$queryType =  !empty($_GET['type']) ? $_GET['type'] : false;
$querySaleRent =  !empty($_GET['saleRent']) ? $_GET['saleRent'] : false;
$queryBedrooms =  !empty($_GET['bedrooms']) ? $_GET['bedrooms'] : false;


$jPropertiesData = json_decode(file_get_contents(__DIR__ . '/../data/properties.json'));

//Create an object of all passed cities properties
$citiesPassedProperties = new stdClass();
foreach ($jPropertiesData as $key => $property) {
    if ($queryCity == $property->address->city) {
        $citiesPassedProperties->$key = $property;
    }
    if (!$queryCity) {
        $citiesPassedProperties = $jPropertiesData;
    }
}


//Create an object of all passed type properties based on the previous passed object
$typePassedProperties = new stdClass();
foreach ($citiesPassedProperties  as $key => $property) {
    if ($queryType == $property->type) {
        $typePassedProperties->$key = $property;
    }

    if (!$queryType) {
        $typePassedProperties = $citiesPassedProperties;
    }
}



//Create an object of all passed saleRent properties based on the previous passed object
$saleRentPassedProperties = new stdClass();
foreach ($typePassedProperties  as $key => $property) {
    if ($querySaleRent == $property->saleRent) {
        $saleRentPassedProperties->$key = $property;
    }

    if (!$querySaleRent) {
        $saleRentPassedProperties =  $typePassedProperties;
    }
}

//Create an object of all passed saleRent properties based on the previous passed object
$bedroomsPassedProperties = new stdClass();
foreach ($saleRentPassedProperties  as $key => $property) {
    if ($queryBedrooms == $property->bedrooms) {
        $bedroomsPassedProperties->$key = $property;
    }

    if (!$queryBedrooms) {
        $bedroomsPassedProperties = $saleRentPassedProperties;
    }
}

$displayProperties = $bedroomsPassedProperties;

$displayPropertiesCoordinates = [];
foreach ($displayProperties as $key => $property) {
    array_push($displayPropertiesCoordinates, (object) [
        "id" => $key,
        "latitude" => $property->coordinates->latitude,
        "longitude" => $property->coordinates->longitude
    ]);
}



?>

<?php require_once('elements/header.php');
?>

<!-- Import mapbox dependencies -->
<script src='https://api.mapbox.com/mapbox-gl-js/v1.3.1/mapbox-gl.js'></script>
<link href='https://api.mapbox.com/mapbox-gl-js/v1.3.1/mapbox-gl.css' rel='stylesheet' />

<?php //var_dump($displayProperties)
?>

<main id="properties">
    <div class="container">

        <div class="grid-box">
            <div class="left">
                <div id="properties-map"></div>
            </div>
            <div class="right">
                <div class="all-properties-grid-container">
                    <div class="all-properties-grid">
                        <?php if (count((array) $displayProperties)) :  /* Check if there are even results*/ ?>
                            <?php foreach ($displayProperties as $index => $property) : ?>
                                <!-- single-prop-start -->
                                <a href="property.php?id=<?php echo $property->id ?>" id="property-<?php echo $property->id ?>" class="single-property" data-latitude="<?php echo $property->coordinates->latitude ?>" data-longitude="<?php echo $property->coordinates->longitude ?>" data-id="<?php echo $property->id ?>">
                                    <div class="preview-img" style='background: url("assets/user-uploads/<?php echo $property->img ?>") no-repeat center center; background-size: cover;'>
                                        <h3 class="price"><?php echo $property->currency . $property->price ?></h3>
                                    </div>
                                    <div class="info">
                                        <p class="name"><?php echo $property->name ?></p>
                                        <div class="highlighted-info">
                                            <p class="bedrooms"><?php echo $property->bedrooms ?> bedrooms</p>
                                            <p>&nbsp;|&nbsp;</p>
                                            <p class="type"><?php echo $property->type ?> for <?php echo $property->saleRent ?></p>
                                        </div>

                                        <p class="address">Tomsgardsvej 104, Copenhagen Nv, 2400 Denmark</p>
                                    </div>
                                </a>
                                <!-- single prop end -->
                            <?php endforeach; ?>
                        <?php else :  /*Else display an error message */ ?>
                            <h3>No properties could be found. Try with different search settings</h3>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="search-bottom-fixed">
            <form class="search-box">
                <div class="property-type">
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
                            <p><?php echo $queryType ?? "Apartment" ?></p>
                            <span></span>
                            <input type="hidden" name="type" value="<?php echo $queryType ?? "Apartment" ?>" />
                        </div>
                    </div>
                </div>
                <div class="location">
                    <div class="content">
                        <p class="top">City</p>
                        <input type="text" name="city" class="location" value="<?php echo $queryCity ?? "Copenhagen" ?>">
                    </div>
                </div>
                <div class="sale-rent">
                    <div class="content">
                        <p class="top">Sale/Rent</p>
                        <div class="dropdown">
                            <ul>
                                <li>Sale</li>
                                <li>Rent</li>
                            </ul>
                        </div>
                        <div class="current-selection">
                            <p><?php echo $querySaleRent ?? "Rent" ?></p>
                            <span></span>
                            <input type="hidden" name="saleRent" value="<?php echo $querySaleRent ?? "Rent" ?>" />
                        </div>
                    </div>
                </div>
                <div class="bedrooms">
                    <div class="content">
                        <p class="top">Bedrooms</p>
                        <div class="slider">
                            <input name="bedrooms" type="range" min="1" max="10" step="1" value="<?php echo $queryBedrooms ?? "2" ?>">
                            <div class="value"><?php echo $queryBedrooms ?? "2" ?></div>
                        </div>
                    </div>
                </div>
                <div class="search">
                    <input type="submit" value="SEARCH" />
                </div>
            </form>
        </div>
    </div>
</main>

<!-- Scripts -->
<script>
    const propertiesCoordinates = <?php echo json_encode($displayPropertiesCoordinates) ?>;
</script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="scripts/properties.js" type="text/javascript"></script>
<?php require_once('elements/footer.php'); ?>