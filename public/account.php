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

?>

<?php require_once('elements/header.php'); ?>

<main id="account">
    <div class="container">
        <div class="header">
            <h2 class="title">Account settings</h2>
            <?php echo empty($_GET['activated']) ? null : '<p style="color:green; padding: 0px 0px 13px 10px"> - account activated </p>'; ?>
        </div>

        <div class="grid-box">
            <div class="left">
                <h3>Basic details</h3>
                <form>
                    <div class="content">
                        <p class="top">Name</p>
                        <input type="text" name="name" class="name" value="<?php echo $jCurrentUserData->name; ?>">
                    </div>
                    <div class="content">
                        <p class="top">Email</p>
                        <input readonly type="email" name="email" class="email" value="<?php echo $jCurrentUserData->email; ?>">
                    </div>
                    <input type="submit" value="Update details" class="submit-btn">
                    <input type="hidden" name="userID" value="<?php echo $jCurrentUserData->id; ?>" class="user-id">
                    <input type="hidden" name="userType" value="<?php echo $jCurrentUserData->userType; ?>" class="user-type">
                    <p class="status-message"></p>
                </form>

                <h3 class="box-title">Delete account</h3>
                <p class="delete-info">By deleting your account you will lose all your data.</p>
                <a class="delete-button" href="delete-account.php">Delete my account</a>

                <h3 class="box-title">Logout</h3>
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
            <div class="right" <?php echo $jCurrentUserData->userType == "agents" ? null : 'style="display:none";' ?>>
                <div class="top-header">
                    <div class="hi-agent">
                        <h3 class="box-title">Hello Agent,</h3>
                        <p class="title-desc">here are your <?php echo count($jCurrentUserData->properties) ?> properties</p>
                    </div>
                    <div class="agent-new">
                        <a href="create-property.php" class="create-new-btn">Create new property</a>
                    </div>
                </div>

                <div class="property-wrapper">


                    <?php foreach ($jCurrentUserData->properties as $propertyID) : ?>
                        <div class="property-box">
                            <div class="property-image" style="background: url('assets/user-uploads/<?php echo $jPropertiesData->$propertyID->img ?>') no-repeat center center; background-size: cover;"></div>
                            <div class="info">
                                <h4> <?php echo $jPropertiesData->$propertyID->name ?> </h4>
                                <p class="price"> <?php echo $jPropertiesData->$propertyID->price ?> <?php echo $jPropertiesData->$propertyID->currency ?> </p>
                                <p class="extra-small-info"><?php echo $jPropertiesData->$propertyID->type ?> for <?php echo $jPropertiesData->$propertyID->saleRent ?> | <?php echo $jPropertiesData->$propertyID->bedrooms ?> bedrooms</p>
                            </div>
                            <a href="property.php?id=<?php echo $propertyID; ?>" class="visit-property-btn">Visit</a>
                        </div>

                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</main>
<!-- Scripts -->
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="scripts/account.js" type="text/javascript"></script>
<?php require_once('elements/footer.php'); ?>