<?php
	if ($result = $mysqli->query("SELECT * FROM  `customer_info` WHERE  `email` =  'dannywaldron2@gmail.com'
AND  `phone` =  '6127996883' AND  `devices_iddevices` IS NOT NULL ")) {
    /* fetch associative array */
     $numsrows = $result->num_rows;
	 
	 
	 
    if ($numsrows == 0){
			 $_SESSION['exitcodev2']  = 'email';
    header('Location: ipcustomer.php');
    exit;							
	 } elseif($numsrows == 1){
		while ($row = $result->fetch_assoc()) {
        $uid= $row["idcustomer_users"];
        $cdid= $row["devices_iddevices"];
        $infoid= $row["idcustomer_info"];
     }								
}
       /* free result set */
    $result->close();
	}
?>
