<?php
require_once('./fileloader.php');

$code =  ACLWhitelist($cusid,$mysqli,$masterkey,$db);

if($code == false){
  echo "fail";
}elseif($code == true){
  echo "works";
}else{
  echo "Error";
}
?>