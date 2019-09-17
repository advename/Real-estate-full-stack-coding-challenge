<?php

session_start();

//Check if user is already logged in, then theres no need to open the login/signup page
//Session does exist, redirect to account page
if ($_SESSION) {
    header('Location: account.php');
    //Check if the session is a logged in session and not some other session type
    if (empty($_SESSION['userID'])) {
        header('Location: account.php');
    }
}






?>

<?php require_once('elements/header.php'); ?>

<main id="access">
    <div class="container">
        <div class="card">
            <!-- Tab buttons -->
            <div class="tab">
                <button class="tablinks active" data-tab="login-tab">Log in</button>
                <button class="tablinks" data-tab="signup-tab">Sign up</button>
            </div>

            <!-- Tab content -->
            <div id="login-tab" class="tabcontent" style="display:block">
                <form>
                    <div class="content">
                        <p class="top">Email</p>
                        <input type="email" name="email" class="email">
                    </div>

                    <div class="content">
                        <p class="top">Password</p>
                        <input type="password" name="password" class="password">
                    </div>
                    <input class="submit-btn" type="submit" value="Sign up">
                    <p class="error-messages"></p>
                </form>
            </div>

            <div id="signup-tab" class="tabcontent">
                <form>
                    <div class="content">
                        <p class="top">Name</p>
                        <input type="text" name="name" class="name">
                    </div>
                    <div class="content">
                        <p class="top">Email</p>
                        <input type="email" name="email" class="email">
                    </div>

                    <div class="content">
                        <p class="top">Password</p>
                        <input type="password" name="password" class="password">
                    </div>
                    <div class="content">
                        <p class="top">Repeat Password</p>
                        <input type="password" name="repPassword" class="re-password">
                    </div>
                    <div class="content user-types">
                        <p class="top">Sign me up as an</p>
                        <div class="same-line">
                            <input type="radio" name="userType" value="users" checked class="radio-users">
                            <p>User</p>
                        </div>
                        <div class="same-line">
                            <input type="radio" name="userType" value="agents">
                            <p>Agent</p>
                        </div>
                        <input type="text" class="hidden">
                    </div>
                    <input class="submit-btn" type="submit" value="Sign up">
                    <p class="error-messages"></p>
                </form>
            </div>
        </div>
    </div>
</main>

<!-- Scripts -->
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="scripts/access.js" type="text/javascript"></script>

<?php require_once('elements/footer.php'); ?>