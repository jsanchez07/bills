


/* When the user clicks on the button, 
toggle between hiding and showing the dropdown content */
function myFunction() {
  document.getElementById("myDropdown").classList.toggle("show");
}

// Close the dropdown if the user clicks outside of it
window.onClick = function(event) {
  if (!event.target.matches('.dropbtn')) {
    var dropdowns = document.getElementsByClassName("dropdown-content");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
}

function payDropFunction(storename, dropdownButtonId) {
    document.getElementById(storename).classList.toggle("show-payment-info");
    var element = document.getElementById(dropdownButtonId);
        if(element.textContent === "Pay Now")
            {
                element.textContent = "Close Form"
            }
        else
            element.textContent = "Pay Now";
}


function mouseOver(id_for_cred) {
    document.getElementById(id_for_cred).style.display = "block";
}

function mouseOut(id_for_cred) {
    document.getElementById(id_for_cred).style.display = "none";
}


