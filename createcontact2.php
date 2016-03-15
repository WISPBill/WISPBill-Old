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
// start of post

$fname = inputcleaner($fname,$mysqli);
$lname = inputcleaner($lname,$mysqli);
$org = inputcleaner($org,$mysqli);
$tel = inputcleaner($tel,$mysqli);
$add = inputcleaner($add,$mysqli);
$city = inputcleaner($city,$mysqli);
$zip = inputcleaner($zip,$mysqli);
$state = inputcleaner($state,$mysqli);
$email1 = inputcleaner($email1,$mysqli);
$email2 = inputcleaner($email2,$mysqli);
// end of post

// start of data sanitize and existence check
 if (empty($fname)) {
    // If input feild is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'fname';
    header('Location: createcontact.php');
    exit;
} elseif(empty($lname)){
    // If input feild is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'lname';
    header('Location: createcontact.php');
    exit;
}elseif(empty($org)){
    // If input feild is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'org';
    header('Location: createcontact.php');
    exit;
}elseif(empty($tel)){
    // If input feild is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'tel';
    header('Location: createcontact.php');
    exit;
}elseif(empty($add)){
    // If input feild is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'add';
    header('Location: createcontact.php');
    exit;
}elseif(empty($city)){
    // If input feild is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'city';
    header('Location: createcontact.php');
    exit;
}elseif(empty($zip)){
    // If input feild is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'zip';
    header('Location: createcontact.php');
    exit;
}elseif(empty($state)){
    // If input feild is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'state';
    header('Location: createcontact.php');
    exit;
}elseif(empty($email1)){
    // If input feild is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'email';
    header('Location: createcontact.php');
    exit;
}elseif(empty($email2)){
    // If input feild is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'email';
    header('Location: createcontact.php');
    exit;
}else{
    //resets the code
     $_SESSION['exitcodev2'] = '';
}
//start of email match
if($email1 == $email2){
    // do nothing 
} else {
    // If email match fails it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'email';
    header('Location: createcontact.php');
    exit;
}
// end if and email match

$fname = $mysqli->real_escape_string($fname);
$lname = $mysqli->real_escape_string($lname);
$org = $mysqli->real_escape_string($org);
$tel = $mysqli->real_escape_string($tel);
$add = $mysqli->real_escape_string($add);
$city = $mysqli->real_escape_string($city);
$zip = $mysqli->real_escape_string($zip);
$state = $mysqli->real_escape_string($state);
$email1 = $mysqli->real_escape_string($email1);

if(!filter_var($email1, FILTER_VALIDATE_EMAIL)){
    $_SESSION['exitcodev2'] = 'email';
    header('Location: createcontact.php');
    exit;
  }
else
  {
  //do nothing 
  }
  
// end of data sanitize and existence check
// start of data entry
if ($mysqli->query("INSERT INTO `$db`.`contacts` (`idcontacts`, `fname`, `lname`, `email`, `phone`, `org`, `address`, `city`, `state`, `zip`)
                   VALUES (NULL, '$fname', '$lname', '$email1',
                   '$tel', '$org', '$add', '$city', '$state', '$zip');") === TRUE) {
//nothing 
} else{
    echo'Something went wrong with the database please contact your webmaster';
        exit;
}

// end of data entry
 header('Location: index.php');
?>