var conn = new WebSocket(`ws://${window.location.hostname}:8080`);

var searchParams = new URLSearchParams(window.location.search);
var room = searchParams.get("id");
var rct_clr = [];

fetch("/anon/public/json/styles.json").then(Response => Response.text()).then((data) => {
  rct_clr = JSON.parse(data)["reactclr"];
  console.log(rct_clr);
})

function subscribe(channel) {
  conn.send(JSON.stringify({ command: "subscribe", channel: channel }));
}

function sendMessage(msg) {
  conn.send(JSON.stringify({ command: "message", message: msg }));
}

function updateRctn(data, cmd, self) {
  console.log(typeof data)
  if (typeof data == "object") {

    for (let i = 0; i < data.length; i++) {
      console.log(data.length);
      let elem = document.getElementById(data[i]);
      elemCount = elem.children[1];
      if (cmd[i] == "decrease") {
        elemCount.innerText = parseInt(elemCount.innerText) - 1;
        if (self) {
          elem.dataset.reacted = false;
          elem.children[0].style.color = "white";

        }
      }
      else {
        elemCount.innerText = parseInt(elemCount.innerText) + 1;
        if (self) {
          elem.dataset.reacted = true;
          elem.children[0].style.color = rct_clr[elem.dataset.react];
        }

      }
    }
  }
  else {
    let elem = document.getElementById(data);
    let elemCount = elem.children[1];

    if (cmd == "decrease") {
      elemCount.innerText = parseInt(elemCount.innerText) - 1;

      if (self) {
        elem.dataset.reacted = false;
        elem.children[0].style.color = "white";


      }
    }
    else if (cmd == "increase") {
      elemCount.innerText = parseInt(elemCount.innerText) + 1;
      if (self) {
        elem.dataset.reacted = true;
        elem.children[0].style.color = rct_clr[elem.dataset.react];
      }


    }

  }

}

conn.onopen = function (e) {
  alert("Connection established!");
  alert(room)
  subscribe(room);
}

conn.onmessage = function (e) {
  console.log(e.data)
  var { data, cmd } = JSON.parse(e.data)
  updateRctn(data, cmd, false)

}

function reactor(elem) {
  var reactKey = elem.dataset.id
  const username = elem.dataset.name;
  const currentuser = elem.dataset.cuser;
  const reactData = elem.dataset.react;
  const reacted = elem.dataset.reacted;
  var elemsibling = elem.parentNode.children;
  var sibReacted;
  var self = false;
  var reactArr = [...elem.parentNode.children];
  reactArr.forEach((child) => { child.onclick = false })
  for (i = 0; i < elemsibling.length; i++) {

    if (elemsibling[i].dataset.reacted == "true") {

      sibReacted = elemsibling[i].dataset.react;
      console.log(sibReacted)
      if (sibReacted == reactData) {
        self = true
      }
      break;
    }
    else { sibReacted = false; }

  }
  const Api = `/anon/pages/reactionmodule.php?uname=${username}&cuser=${currentuser}&Rdata=${reactData}&$reacted=${reacted}&sibReacted=${sibReacted}&msgid=${reactKey}&react=${reactData}&self=${self}`


  fetch(Api, {
    method: "GET",
    headers: { "X-Requested-With": "XMLHttpRequest" }
  })
  .catch((err) => {
     alert(err)
     })
     .then(Response => Response.text())
     .then((fetchdata) => {
    var { data, cmd } = JSON.parse(fetchdata);
    [...elem.parentNode.children].forEach((child) => {
      child.onclick = function () { reactor(child) }

    })

    updateRctn(data, cmd, true);
    sendMessage(fetchdata);
    
  })
}



