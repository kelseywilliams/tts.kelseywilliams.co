var data = {};

// Classic AJAX call using classic stuff
function successMsg(uri) {
    var httpRequest;
    httpRequest = new XMLHttpRequest();

    if (!httpRequest) {
        alert('Giving up :( Cannot create an XMLHTTP instance');
        return false;
    }
    httpRequest.onreadystatechange = () => {
        if (httpRequest.readyState === XMLHttpRequest.DONE) {
            if (httpRequest.status === 200 && httpRequest.responseText !== "empty") {
                alert(httpRequest.responseText);
            }
        }
    }
    httpRequest.open('GET', uri);
    httpRequest.send();
}

// Slick AJAX call using some fetch stuff and promises.
function insertData(uri){
    httpRequest = new XMLHttpRequest();
    if(!httpRequest){
        return false;
    }
    fetch(uri).then(function(httpRequest) {
        httpRequest.text().then(function(text) {
          data = text;
        })
        .then(function(){
            data = JSON.parse(data);

            var fragment = new DocumentFragment();
            var msgs = document.getElementById("msgs");

            var count = Object.keys(data).length;
            for(var i=count-1; i >= 0; i--){
                var container = document.createElement("div")
                container.className = "container";
                container.id = i.toString();

                var t = document.createElement("div");
                t.className = "time";
                t.id = i.toString();
                var timeText = document.createTextNode(data[i]["time"]);
                t.appendChild(timeText);

                var msg = document.createElement("div")
                msg.className = "msg";
                msg.id = i.toString();
                var MsgText = document.createTextNode(data[i]["data"]);
                msg.appendChild(MsgText);

                container.appendChild(t);
                container.appendChild(msg);
                fragment.appendChild(container);
            }
            msgs.appendChild(fragment);
        });
      });
}

insertData("api.php");
successMsg("success.php");

// W3 schools said this code snippet would tie the submit key to enter key.  I trust them
var input = document.getElementById("data");

// Execute a function when the user releases a key on the keyboard
input.addEventListener("keydown", function(event) {
  // Number 13 is the "Enter" key on the keyboard
  if (event.keyCode === 13) {
    // Cancel the default action, if needed
    event.preventDefault();
    // Trigger the button element with a click
    document.getElementById("submit").click();
  }
});

var date = new Date();
var time = date.toLocaleDateString() + " " + date.toLocaleTimeString()
document.forms[0].elements["time"].value = time;
