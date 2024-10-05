function preview(elem,multi)
{
   // Get the ID of the element
      if (multi){var id = `${elem.innerText}` }else {var id = elem.dataset.msgid};
   // Get all elements with class "messages"
  var userid = elem.dataset.user;
console.log(userid)
 const httpreq = new XMLHttpRequest()
 httpreq.onload  = async function(e){
if(httpreq.status === 200){

    console.log(this.responseText)
 window.location = "/anon/pages/preview.php"
      }
   
}


 httpreq.open("GET",`/anon/pages/preview.php?id=${id}&userid=${userid}&multi=${multi}`);

 httpreq.send();
}



