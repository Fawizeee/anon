<?php
    namespace Anon\Src;

require_once $_SERVER['DOCUMENT_ROOT']."/anon/bootstrap.php";
$db = $db->db;
if (isset($_GET) && isset($_GET["selectedid"])) {

  $selectedids = (array) json_decode($_GET["selectedid"]);
  $id = uniqid(mt_rand(1, 15));
  $insertids = [];
  foreach ($selectedids as $selectedid) {
    $insertids[] = "('$id','$selectedid')";
  }
  $insertids = implode(",", $insertids);

  $stmt = implode("", ["INSERT INTO SELECTTABLE VALUES", $insertids]);

  $sql = $db->prepare($stmt);
  $ret = $sql->execute();
  if ($ret) {
    $msg_URl = "$_SERVER[REQUEST_SCHEME]://$_SERVER[SERVER_NAME]/anon/pages/preview";
    $param = "selectid=$id";
    echo $msg_URl . "?" . $param;
  }
}
