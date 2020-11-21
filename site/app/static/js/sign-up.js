var form = document.getElementById("sign-up-form");

var scoutToggle = document.getElementById("scout-toggle");
var guideToggle = document.getElementById("guide-toggle");
var scoutCard = document.getElementById("scout-card");
var guideCard = document.getElementById("guide-card");

var scoutCounty = document.getElementById("scout-county")
var avonCountyToggle = document.getElementById("avon-county-toggle");
var otherScoutCountyToggle = document.getElementById("other-scout-county-toggle");
var otherScoutCountyName = document.getElementById("other-scout-county")
var avonDistrict = document.getElementById("avon-district");
var otherDistrict = document.getElementById("other-district");
var scoutGroup = document.getElementById("scout-group");
var scoutTroop = document.getElementById("scout-troop");

var guideCounty = document.getElementById("guide-county")
var bsgCountyToggle = document.getElementById("bsg-county-toggle");
var snCountyToggle = document.getElementById("sn-county-toggle");
var otherGuideCountyToggle = document.getElementById("other-guide-county-toggle");
var bsgDivision = document.getElementById("bsg-division");
var snDivision = document.getElementById("sn-division");
var otherDivision = document.getElementById("other-division");
var guideUnit = document.getElementById("guide-unit");

var contactCard = document.getElementById("contact-card");
var submitCard = document.getElementById("submit-card");

var submitButton = document.getElementById("signup-submit");
var submitSpinner = document.getElementById("signup-processing");

document.body.onload = function() {
  scoutToggle.onclick = function () {
    scoutCard.removeAttribute("hidden");
    guideCard.setAttribute("hidden", "true");
  };

  guideToggle.onclick = function () {
    scoutCard.setAttribute("hidden", "true");
    guideCard.removeAttribute("hidden");
  };

  avonCountyToggle.onclick = function () {
    avonDistrict.removeAttribute("hidden");
    otherDistrict.setAttribute("hidden", "true");
    scoutGroup.removeAttribute("hidden");
    scoutTroop.removeAttribute("hidden");
    contactCard.removeAttribute("hidden");
    submitCard.removeAttribute("hidden");
    scoutCounty.classList.add("uk-margin");
  };

  otherScoutCountyToggle.onclick = function () {
    avonDistrict.setAttribute("hidden", "true");
    otherDistrict.removeAttribute("hidden");
    scoutGroup.removeAttribute("hidden");
    scoutTroop.removeAttribute("hidden");
    contactCard.removeAttribute("hidden");
    submitCard.removeAttribute("hidden");
    scoutCounty.classList.add("uk-margin");
  };

  bsgCountyToggle.onclick = function () {
    bsgDivision.removeAttribute("hidden");
    snDivision.setAttribute("hidden", "true");
    otherDivision.setAttribute("hidden", "true");
    guideUnit.removeAttribute("hidden");
    contactCard.removeAttribute("hidden");
    submitCard.removeAttribute("hidden");
    guideCounty.classList.add("uk-margin");
  };

  snCountyToggle.onclick = function () {
    snDivision.removeAttribute("hidden");
    bsgDivision.setAttribute("hidden", "true");
    otherDivision.setAttribute("hidden", "true");
    guideUnit.removeAttribute("hidden");
    contactCard.removeAttribute("hidden");
    submitCard.removeAttribute("hidden");
    guideCounty.classList.add("uk-margin");
  };

  otherGuideCountyToggle.onclick = function () {
    otherDivision.removeAttribute("hidden");
    bsgDivision.setAttribute("hidden", "true");
    snDivision.setAttribute("hidden", "true");
    guideUnit.removeAttribute("hidden");
    contactCard.removeAttribute("hidden");
    submitCard.removeAttribute("hidden");
    guideCounty.classList.add("uk-margin");
  };

  form.onsubmit = function (ev) {
    ev.preventDefault();
    submitButton.setAttribute('hidden', 'true');
    submitSpinner.removeAttribute('hidden');
  }
};
