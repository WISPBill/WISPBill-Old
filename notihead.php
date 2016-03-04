<?php
$mysqli = new mysqli("$ip", "$username", "$password", "$db");

if(isset($_SESSION['adminid'])){
$adminid = $_SESSION['adminid'];
if ($result = $mysqli->query("SELECT * FROM `notifications` WHERE `readyn`
= '0' and `towho` = 'all' or `readyn`= '0' and `towho` = '$adminid' ORDER BY `notifications`.`idnotifications` DESC")) {
    $notitotal = mysqli_num_rows($result);
         
     if($notitotal < 7){
      $noticode = 'success'; // Green
     }elseif(7< $notitotal and $notitotal < 15){
      $noticode = 'warning'; //Orange
     }elseif($notitotal > 15){
      $noticode = 'danger'; // Red
     }
     
    /* free result set */
    $result->close();
    
      if ($result = $mysqli->query("SELECT * FROM `notifications` WHERE `readyn`
= '0' and `towho` = 'all' or `readyn`= '0' and `towho` = '$adminid' ORDER BY `notifications`.`idnotifications` DESC limit 1;")) {
        
    while ($row = $result->fetch_assoc()) {
     $source= $row["fromwho"];
     $notiid= $row["idnotifications"];
     $notimesg= $row["content"];
     $notitimestamp = $row["date"];
      }
      $unixTimestamp = strtotime("$notitimestamp");
      $currenttime = time();
      
      $stime = $currenttime - $unixTimestamp;
      
      $notitime = $stime/60;
      $notitime = round($notitime);
      $notitime = $notitime.' Mins';
      if($source == 'system'){
        $notisource = 'System Automatic Alert';
        $notiimage = $sysimg;
      }elseif(is_numeric($source)){
        if ($result3 = $mysqli->query("SELECT * FROM `admin_users` WHERE `idadmin` = $source")) {
				/* fetch associative array */
				 while ($row3 = $result3->fetch_assoc()) {
					 $notisource = $row3["fname"];
                     $notiimage = $row3["img"];
					 }
  
					$result3->close();
						}
      }else {
        $notisource = $source;
         $notiimage = $unkownimg;
      }
      
      /* free result set */
    $result->close();
      }
}
	// Start of Calaender even getter 
	if ($result = $mysqli->query("SELECT * FROM `tasks` WHERE `admin_users_idadmin` = '$adminid' and `start_date_time` >  '$currenttime'")) {
		$curtime = time();
    $calevents = mysqli_num_rows($result);
	}
	
	
}else{
	//nothing
}
?>