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
$capacity = $_POST["capacity"];
$mastersite = $_POST["site"];
$masterport = $_POST["port"];
$slavesite = $_POST["slavesite"];
$slaveport = $_POST["slaveport"];
// end of post

// start of data sanitize and existence check
 if (empty($capacity)) {
    // If name is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'capacity';
    header('Location: addlink.php');
    exit;
} elseif(empty($mastersite)){
    // If price is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2']= 'site';
    header('Location: addlink.php');
    exit;
}
elseif(empty($masterport)){
    // If up is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'port';
    header('Location: addlink.php');
    exit;
}
elseif(empty($slavesite)){
    // If email is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'slavesite';
    header('Location: addlink.php');
    exit;
}elseif(empty($slaveport)){
    $_SESSION['exitcodev2'] = 'slaveport';
    header('Location: addlink.php');
    exit;
}else{
    // do nothing 
} // end if

$mastersite = $mysqli->real_escape_string($mastersite);
$masterport = $mysqli->real_escape_string($masterport);
$slavesite = $mysqli->real_escape_string($slavesite);
$slaveport = $mysqli->real_escape_string($slaveport);
$capacity = $mysqli->real_escape_string($capacity);
// end of data sanitize and existence check
//start of data entry for system DB
if ($mysqli->query("INSERT INTO `$db`.`links` (`idlinks`, `capacity`, `status`, `master_site`, `slave_site`,
`master_port`, `slave_port`) VALUES (NULL, '$capacity', NULL, '$mastersite', '$slavesite',
'$masterport', '$slaveport');") === TRUE) {
} else{
   echo'Something went wrong with the database please contact your webmaster';
      exit;
}

header('Location: menue.php');
// end of file
?>