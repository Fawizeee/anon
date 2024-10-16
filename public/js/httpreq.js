var conn = new WebSocket('ws://192.168.137.1:8080');

var searchParams = new URLSearchParams(window.location.search);
var room = searchParams.get("name");

var rct_clr = [];

fetch("/anon/public/json/styles.json").then(Response=>Response.text()).then((data)=>{
  rct_clr=JSON.parse(data)["reactclr"];
  console.log( rct_clr);
})
 
 function subscribe(channel) {
     conn.send(JSON.stringify({command: "subscribe", channel: channel}));
}

function sendMessage(msg) {
   conn.send(JSON.stringify({command: "message", message: msg}));
}

function updateRctn(data,cmd,self){ 
  console.log(typeof data)
  if (typeof data == "object"){

  for(let i = 0; i<data.length; i++){
        console.log(data.length)
  let elem = document.getElementById(data[i])
  elemCount = elem.children[1]
     if(cmd[i]=="decrease"){
       elemCount.innerText = parseInt(elemCount.innerText)-1;
       if(self){
        elem.dataset.reacted = false;
        elem.children[0].style.color ="white";
      
      }
     }
     else{
      elemCount.innerText = parseInt(elemCount.innerText)+1;
      if(self){
        elem.dataset.reacted = true;
        elem.children[0].style.color = rct_clr[elem.dataset.react];
      }
  
     }
}}
else{
  let elem = document.getElementById(data);
  let elemCount = elem.children[1];

  if(cmd=="decrease"){
    elemCount.innerText = parseInt(elemCount.innerText)-1;
  
    if(self){
      elem.dataset.reacted = false;
      elem.children[0].style.color ="white";
      
    
    }
  }
  else if(cmd=="increase"){
    elemCount.innerText = parseInt(elemCount.innerText)+1;
    if(self){
      elem.dataset.reacted = true;
      elem.children[0].style.color = rct_clr[elem.dataset.react];
    }
  

  }

}

}

conn.onopen =  function(e) {
   alert("Connection established!");
   console.log(room)
   subscribe(room);
 }

 conn.onmessage = function(e) {
  console.log(e.data)
  var {data,cmd} = JSON.parse(e.data)
  updateRctn(data,cmd,false)

 }

function reactor(elem){
    var reactKey = elem.dataset.id
    const username = elem.dataset.name ;
    const currentuser= elem.dataset.cuser;
    const reactData = elem.dataset.react;
    const reacted = elem.dataset.reacted;
    var elemsibling = elem.parentNode.children;
    var sibReacted ;
    var self = false;
    var reactArr  =	[...elem.parentNode.children];
    reactArr.forEach((child)=>{child.onclick = false})
    for( i = 0; i<elemsibling.length;i++){

    if( elemsibling[i].dataset.reacted=="true"){

         sibReacted=elemsibling[i].dataset.react;
         console.log(sibReacted)
         if(sibReacted == reactData){
            self= true
         }
         break;
    }
    else {sibReacted=false;}

    }

    const httpreq = new XMLHttpRequest()
    httpreq.onload  = async function(){
if(httpreq.status === 200){
  console.log(this.responseText)
  var {data,cmd} = JSON.parse(this.responseText);
  [...elem.parentNode.children].forEach((child)=>{

    child.onclick = function(){reactor(child)}


}) 
    await sendMessage(this.responseText);
    updateRctn(data,cmd,true);
         }
      
}
    
    httpreq.open("GET",`/anon/pages/reactionmodule.php?uname=${username}&cuser=${currentuser}&Rdata=${reactData}&$reacted=${reacted}&sibReacted=${sibReacted}&msgid=${reactKey}&react=${reactData}&self=${self}`)
    httpreq.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    httpreq.send();
} 


    
