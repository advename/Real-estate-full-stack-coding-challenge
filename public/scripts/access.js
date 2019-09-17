/**
 * =====================================================
 * Login handler
 * =====================================================
 */
const loginForm = document.querySelector("#access #login-tab form");
const loginFormButton = document.querySelector(
  "#access #login-tab form .submit-btn"
);
const loginFormEmail = document.querySelector("#access #login-tab form .email");
const loginFormPassword = document.querySelector(
  "#access #login-tab form .password"
);
const loginFormErrorField = document.querySelector(
  "#access #login-tab form .error-messages"
);

let loginFormErrors = [];

loginFormButton.addEventListener("click", tryLoginUser);

//Try to login the user
function tryLoginUser(evt) {
  evt.preventDefault(); //Stop default form behaviour (no redirect,...);
  loginFormErrorField.innerHTML = "";
  loginFormErrors = []; //reset error messages
  //Create new form data type - since PHP $_POST[] expects data to be sent as a form and not as JSON
  var formData = new FormData();
  formData.append("email", loginFormEmail.value);
  formData.append("password", loginFormPassword.value);

  axios
    .post("api/login-user", formData)
    .then(function(response) {
      console.log(response.data);

      if (response.data.statusCode == 200) {
        //User/password credentials successfully
        //Session has been created by the server, aka, the user is logged in now
        //Redirect to account page
        window.location.href = "account.php";
      } else {
        loginFormErrors.push(response.data.message);
        loginFormDisplayErrorMessages();
      }
    })
    .catch(function(error) {
      console.log(error);
      // return cancelSignupEvent(evt);
    });
}

//Display error messages on login form
function loginFormDisplayErrorMessages() {
  loginFormErrorField.innerHTML = loginFormErrors.join("<br>");
}

/**
 * =====================================================
 * Sign up handler
 * =====================================================
 */

const signupForm = document.querySelector("#access #signup-tab form");
const signupFormButton = document.querySelector(
  "#access #signup-tab form .submit-btn"
);
const signupFormName = document.querySelector("#access #signup-tab form .name");
const signupFormEmail = document.querySelector(
  "#access #signup-tab form .email"
);
const signupFormUserType = document.querySelector(
  "#access #signup-tab form .radio-users"
);
const signupFormPassword = document.querySelector(
  "#access #signup-tab form .password"
);
const signupFormRePassword = document.querySelector(
  "#access #signup-tab form .re-password"
);
const signupFormErrorField = document.querySelector(
  "#access #signup-tab form .error-messages"
);

let signupFormErrors = [];

signupFormButton.addEventListener("click", validateSignupInputFields);

function validateSignupInputFields(evt) {
  signupFormErrors = [];
  signupFormErrorField.innerHTML = ""; //clear error fields

  //Stop submit action for noew
  evt.preventDefault();

  //Create new form data type - since PHP $_POST[] expects data to be sent as a form and not as JSON
  var formData = new FormData();
  formData.append("email", signupFormEmail.value);

  //check if passwords match
  if (signupFormPassword.value === signupFormRePassword.value) {
    //POST data to api and check if user exists, if not, submit form
    axios
      .post("api/is-email-already-registered", formData)
      .then(function(response) {
        console.log(response.data);

        if (response.data.statusCode == 200) {
          //sign up user
          signupFormSignUpUser();
        } else {
          //display error code

          signupFormErrors.push(response.data.message);
          signupFormDisplayErrorMessages();
        }
      })
      .catch(function(error) {
        console.log(error);
        // return cancelSignupEvent(evt);
      });
  } else {
    // return cancelSignupEvent(evt);
    signupFormErrors.push("Passwords do not match");
    signupFormDisplayErrorMessages();
  }
}

/**
 * Display Error messages for signup form
 */

function signupFormDisplayErrorMessages() {
  signupFormErrorField.innerHTML = signupFormErrors.join("<br>");
}

/**
 * Signup the user
 */
function signupFormSignUpUser() {
  let userType = signupFormUserType.checked ? "users" : "agents";

  //Create new form data type - since PHP $_POST[] expects data to be sent as a form and not as JSON
  var formData = new FormData();
  formData.append("name", signupFormName.value);
  formData.append("email", signupFormEmail.value);
  formData.append("userType", userType);
  formData.append("password", signupFormPassword.value);
  formData.append("rePassword", signupFormRePassword.value);

  axios
    .post("api/signup", formData)
    .then(function(response) {
      console.log(response.data);

      if (response.data.statusCode == 200) {
        //sign up user
        document.querySelector("#access .card").innerHTML = `
        <h3>Thanks for signing up</h3>
        <br>
        <p>An activation link has been sent to your email</p>
        <br>
        <a href="index.php">&lt; return to frontpage</a>
        `;
      } else {
        signupFormErrors.push(response.data.message);
        signupFormDisplayErrorMessages();
      }
    })
    .catch(function(error) {
      console.log(error);
      // return cancelSignupEvent(evt);
    });
}

/**
 * Tabs handler
 */

const accessTabButton = document.querySelectorAll("#access .card .tab button");

accessTabButton.forEach(btn => {
  btn.addEventListener("click", toggleLoginSignup);
});

function toggleLoginSignup(evt) {
  // Get all elements with class="tabcontent" and hide them
  const tabcontents = document.querySelectorAll("#access .card .tabcontent");
  tabcontents.forEach(content => {
    content.style.display = "none";
  });

  // Get all elements with class="tabcontent" and hide them
  const tablinks = document.querySelectorAll("#access .card .tablinks");
  tablinks.forEach(link => {
    link.classList.remove("active");
  });

  // console.log(evt.currentTarget.dataset.tab);
  // Show the current tab, and add an "active" class to the button that opened the tab
  document.querySelector(
    "#access #" + evt.currentTarget.dataset.tab
  ).style.display = "block";
  evt.currentTarget.className += " active";
}
