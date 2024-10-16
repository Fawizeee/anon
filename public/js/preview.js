function preview(elem, multi) {
   // Get the ID of the element
   if (multi) { var id = `${elem.innerText}` } else { var id = elem.dataset.msgid };
   // Get all elements with class "messages"
   var userid = elem.dataset.user;
   console.log(userid)
   const httpreq = new XMLHttpRequest()
   httpreq.onload = async function (e) {
      if (httpreq.status === 200) {

         console.log(this.responseText)
         window.location = "/anon/pages/preview.php"
      }

   }


   httpreq.open("GET", `/anon/pages/preview.php?id=${id}&userid=${userid}&multi=${multi}`);

   httpreq.send();
}


var selectids = [];
var selectedLen = 0
var saveselected = document.getElementById("saveselected");
function display() {
   selectedLen == 0 ?
      saveselected.style.display = "none" :
      saveselected.style.display = "block";

}
function selectid(e) {
   if (e.dataset.selected == 0) {
      e.dataset.selected = 1;
      selectids.push(e.dataset.msgid);
      selectedLen = selectedLen + 1;
   }
   else if (e.dataset.selected == 1) {
      e.dataset.selected = 0;

      var ind = selectids.lastIndexOf(e.dataset.msgid);
      selectids.splice(ind, 1);
      selectedLen = selectedLen - 1;
   }
   display();
   console.log(selectids)
}

function saveSelected() {
   alert("yes");
   const selectedidjson = JSON.stringify(selectids);
   //  var result =  fetch(`/anon/module/saveselected?selectedid=${selectedidjson}`).catch(err=>"err").then(result=>"result");
   //  console.log(result+"jhjggh");

   fetch(`/anon/module/saveselected?selectedid=${selectedidjson}`).then(Response => Response.text()).then((data) => {

      console.log(data)
   })
}