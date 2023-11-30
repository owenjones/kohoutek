// const axios = require('axios');

var section = document.getElementById("sign-up");
var form = document.getElementById("sign-up-form");

var scoutToggle = document.getElementById("scout-toggle");
var guideToggle = document.getElementById("guide-toggle");
var scoutCard = document.getElementById("scout-card");
var guideCard = document.getElementById("guide-card");

var scoutDistrict = document.getElementById("scout-district");
var scoutGroup = document.getElementById("scout-group");
var scoutTroop = document.getElementById("scout-troop");

var guideCounty = document.getElementById("guide-county")
var bsgCountyToggle = document.getElementById("bsg-county-toggle");
var snCountyToggle = document.getElementById("sn-county-toggle");
var bsgDivision = document.getElementById("bsg-division");
var snDivision = document.getElementById("sn-division");
var guideUnit = document.getElementById("guide-unit");

var contactCard = document.getElementById("contact-card");
var submitCard = document.getElementById("submit-card");

var submitButton = document.getElementById("signup-submit");
var submitSpinner = document.getElementById("signup-processing");
var submitError = document.getElementById("submit-error");

var complete = document.getElementById("complete-card");

var guidingSelected = false;
var submitLock = false;

document.body.onload = function () {
  scoutToggle.onclick = function () {
    guideToggle.checked = false;
    scoutCard.removeAttribute("hidden");
    guideCard.setAttribute("hidden", true);
    contactCard.removeAttribute("hidden");
    submitCard.removeAttribute("hidden");
    guidingSelected = false;
  };

  guideToggle.onclick = function () {
    scoutToggle.checked = false;
    scoutCard.setAttribute("hidden", true);
    guideCard.removeAttribute("hidden");
    contactCard.setAttribute("hidden", true);
    submitCard.setAttribute("hidden", true);
    bsgDivision.setAttribute("hidden", true);
    snDivision.setAttribute("hidden", true);
    bsgCountyToggle.checked = false;
    snCountyToggle.checked = false;
    guideUnit.setAttribute("hidden", true);
    guideCounty.classList.remove("uk-margin");
  };

  bsgCountyToggle.onclick = function () {
    bsgDivision.removeAttribute("hidden");
    snDivision.setAttribute("hidden", true);
    guideUnit.removeAttribute("hidden");
    contactCard.removeAttribute("hidden");
    submitCard.removeAttribute("hidden");
    guideCounty.classList.add("uk-margin");
  };

  snCountyToggle.onclick = function () {
    snDivision.removeAttribute("hidden");
    bsgDivision.setAttribute("hidden", true);
    guideUnit.removeAttribute("hidden");
    contactCard.removeAttribute("hidden");
    submitCard.removeAttribute("hidden");
    guideCounty.classList.add("uk-margin");
  };

  form.onsubmit = async function (ev) {
    ev.preventDefault();

    if (!submitLock) {
      submitLock = true;
      submitButton.setAttribute("hidden", true);
      submitSpinner.removeAttribute("hidden");
      submitError.setAttribute("hidden", true);

      await axios.post('/sign-up', document.querySelector("#sign-up-form"), {
        headers: {
          'Content-Type': 'application/json'
        }
      }).then(function (response) {
        form.setAttribute("hidden", true);
        complete.removeAttribute("hidden");
        complete.scrollIntoView({
          behaviour: "smooth",
          block: "center"
        });
      }).catch(function (error) {
        submitSpinner.setAttribute("hidden", true);
        submitButton.removeAttribute("hidden");
        submitError.innerText = error.response.data.message;
        submitError.removeAttribute("hidden");
        submitError.scrollIntoView({
          behaviour: "smooth",
          block: "center"
        });
        submitLock = false;
      });
    }
  }
};
