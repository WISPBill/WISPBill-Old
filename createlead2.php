<?php
/*
    WISPBill a PHP based ISP billing platform
    Copyright (C) 2015  Turtles2

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero General Public License as published
    by the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU Affero General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

	@turtles2 on ubiquiti community, DSLReports and Netonix 
 */
require_once('./session.php');
require_once('./fileloader.php');
$mysqli = new mysqli("$ip", "$username", "$password", "$db");
if (empty($_SESSION['email'])){
// start of post
$fname = $_POST["fname"];
$lname = $_POST["lname"];
$tel = $_POST["tel"];
$add = $_POST["add"];
$city = $_POST["city"];
$zip = $_POST["zip"];
$state = $_POST["state"];
$email1 = $_POST["email"];
$email2 = $_POST["email2"];
$source = $_POST["source"];
$type = $_POST["type"];
// end of post

// start of data sanitize and existence check
 if (empty($fname)) {
    // If input feild is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'fname';
    header('Location: createlead.php');
    exit;
} elseif(empty($lname)){
    // If input feild is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'lname';
    header('Location: createlead.php');
    exit;
}elseif(empty($tel)){
    // If input feild is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'tel';
    header('Location: createlead.php');
    exit;
}elseif(empty($add)){
    // If input feild is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'add';
    header('Location: createlead.php');
    exit;
}elseif(empty($city)){
    // If input feild is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'city';
     header('Location: createlead.php');
    exit;
}elseif(empty($zip)){
    // If input feild is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'zip';
     header('Location: createlead.php');
    exit;
}elseif(empty($state)){
    // If input feild is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'state';
     header('Location: createlead.php');
    exit;
}elseif(empty($email1)){
    // If email is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'email';
     header('Location: createlead.php');
    exit;
}
elseif(empty($email2)){
    // If email is empty it goes back to the fourm and informs the user 
    $_SESSION['exitcodev2'] = 'email2';
    header('Location: createlead.php');
    exit;
} elseif(empty($source)){
    // If email is empty it goes back to the fourm and informs the user 
    $_SESSION['exitcodev2'] = 'source';
    header('Location: createlead.php');
    exit;
} elseif(empty($type)){
    // If email is empty it goes back to the fourm and informs the user 
    $_SESSION['exitcodev2'] = 'type';
    header('Location: createlead.php');
    exit;
}else{
    // do nothing 
} // end if

$fname = inputcleaner($fname,$mysqli);
$lname = inputcleaner($lname,$mysqli);
$tel = inputcleaner($tel,$mysqli);
$add = inputcleaner($add,$mysqli);
$city = inputcleaner($city,$mysqli);
$zip = inputcleaner($zip,$mysqli);
$state = inputcleaner($state,$mysqli);
$email1 = inputcleaner($email1,$mysqli);
$email2 = inputcleaner($email2,$mysqli);
$source = inputcleaner($source,$mysqli);
$type = inputcleaner($type,$mysqli);

if(!filter_var($email1, FILTER_VALIDATE_EMAIL)){
    $_SESSION['exitcodev2'] = 'Email was Not Valid';
     header('Location: createlead.php');
    exit;
  }
else
  {
  //do nothing 
  }
//start of email match
if($email1 == $email2){
    // do nothing 
} else {
    // If email match fails it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'Emails do not Match';
     header('Location: createlead.php');
    exit;
}
// end if and email match
$gps = geocode($add, $city, $state, $zip);
if ($gps == 'No Match'){
    // Geocoder found no GPS
    if ($mysqli->query("INSERT INTO `$db`.`customer_info`
                   (`idcustomer_info`,`idcustomer_plans`,
                   `devices_iddevices`,  `fname`,
                   `lname`, `phone`, `address`, `city`, `zip_code`, `state`,
                   `email`, `lat`, `lon`, `creation_date`, `source`, `type`) VALUES
                   (NULL, NULL, NULL,
                   '$fname', '$lname', '$tel', '$add',
                   '$city', '$zip', '$state', '$email1',
                   NULL, NULL, CURRENT_TIMESTAMP, '$source', '$type');") === TRUE) {
//nothing 
} else{
    echo'Something went wrong with the database please contact your webmaster';
        exit;
}
echo'<form action="createlead2.php"method="post">
<h3> Geocoder failed enter the GPS info by hand</h3>
  	    <fieldset>
        
                             <br><label>Latitude</label>
  	            <input type="text" 
  	             name="lat"/></br>
                 
  	            <br><label>Longitude</label>
  	            <input type="text" 
  	             name="lon"/></br>
                    
  	            <br><button type="submit">
  	                Submit Info
  	            </button></br>   
  	    </fieldset>
  	    </form>';
 $_SESSION['email'] = $email1;
    exit;
} elseif(empty($gps)){
    echo 'Geocoder has failed contact your webmaster';
    exit;
}else{
    // Shoud get a lat lon
    $lat = $gps['lat'];
    $lon = $gps['lon'];
}
// end of data sanitize and existence check
// start of data entry
if ($mysqli->query("INSERT INTO `$db`.`customer_info`
                   (`idcustomer_info`, `idcustomer_plans`,
                   `devices_iddevices`, `fname`,
                   `lname`, `phone`, `address`, `city`, `zip_code`, `state`,
                   `email`, `lat`, `lon`, `creation_date`, `source`, `type`) VALUES
                   (NULL, NULL,  NULL,
                   '$fname', '$lname', '$tel', '$add',
                   '$city', '$zip', '$state', '$email1',
                   '$lat', '$lon', CURRENT_TIMESTAMP, '$source', '$type');") === TRUE) {
//nothing 
} else{
    echo'Something went wrong with the database please contact your webmaster';
        exit;
}

// end of data entry
}else{
$lat = $_POST["lat"];
$lon = $_POST["lon"];
 if (empty($lat)) {
    // If input feild is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'No Latitude was Submitted';
    header('Location: createlead2.php');
    exit;
} elseif(empty($lon)){
    // If input feild is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'No Longitude was Submitted';
    header('Location: createlead2.php');
    exit;
}else{
    //Do nothing
}
$email1 = $_SESSION['email'];
if ($mysqli->query("UPDATE `$db`.`customer_info`
                   SET `lat` = '$lat', `lon` = '$lon' WHERE
                   `customer_info`.`email` = '$email1';") === TRUE) {
//nothing 
} else{
    echo'Something went wrong with the database please contact your webmaster';
        exit;
}

}

if ($result = $mysqli->query("SELECT * FROM `customer_info` WHERE `email` = '$email1'")) {
    /* fetch associative array */
     while ($row = $result->fetch_assoc()) {
     $iid= $row["idcustomer_info"];
}
       /* free result set */
    $result->close();
}// end if

if ($mysqli->query("INSERT INTO `$db`.`customer_external`
                   (`customer_info_idcustomer_info`, `billing`, `communication_preferences`, `billing_mode`)
                   VALUES ('$iid', NULL, NULL, NULL);") === TRUE) {
//nothing 
} else{
    echo'Something went wrong with the database please contact your webmaster';
        exit;
}

header('Location: index.php');
?>