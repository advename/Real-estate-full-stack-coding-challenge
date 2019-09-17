const accountUpdateButton = document.querySelector(
  "#account .left form .submit-btn"
);
const accountUpdateName = document.querySelector("#account .left form .name");
const accountUpdateUserID = document.querySelector(
  "#account .left form .user-id"
);
const accountUpdateUserType = document.querySelector(
  "#account .left form .user-type"
);
const accountUpdateStatusMessage = document.querySelector(
  "#account .left form .status-message"
);

accountUpdateButton.addEventListener("click", updateUserDetails);

function updateUserDetails(evt) {
  evt.preventDefault();
  accountUpdateStatusMessage.textContent = "";

  let formData = new FormData();
  formData.append("name", accountUpdateName.value);
  formData.append("userID", accountUpdateUserID.value);
  formData.append("userType", accountUpdateUserType.value);

  axios
    .post("api/update-user-details", formData)
    .then(function(response) {
      console.log(response.data);
      if (response.data.statusCode == 200) {
        accountUpdateStatusMessage.style.color = "green";
        accountUpdateStatusMessage.textContent = response.data.message;
      } else {
        accountUpdateStatusMessage.style.color = "red";
        accountUpdateStatusMessage.textContent = response.data.message;
      }
    })
    .catch(function(error) {
      console.log(error);
      // return cancelSignupEvent(evt);
    });
}
