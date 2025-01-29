function opentab(evt, tabName) {
  // Declare all variables
  var i, tabcontent, tablinks;

  // Get all elements with class="tabcontent" and hide them
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }

  // Get all elements with class="tablinks" and remove the class "active"
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }

  // Show the current tab, and add an "active" class to the button that opened the tab
  document.getElementById(tabName).style.display = "block";
  evt.currentTarget.className += " active";
}


function login() {
  window.open("../General/login.php", "_self");

}

function signup() {
  window.open("../General/signup.php", "_self");

}

document.getElementById("defaultOpen").click();

//open functions//
function spotifyopen() {
  window.open("https://open.spotify.com/")
}

function elearning() {
  window.open("https://elearn.salcc.edu.lc/")
}

function youtube() {
  window.open("https://www.youtube.com/")
}

function ebscohost() {
  window.open("https://login.ebsco.com/")
}

function googledocs() {
  window.open("https://docs.google.com/")
}

function office() {
  window.open("https://www.microsoft365.com/")
}

function events() {
  window.open("../Student_view/events.php", "_self")
}

function querys() {
  window.open("../Service_forms/Query.php", "_self")
}


// cookies 

