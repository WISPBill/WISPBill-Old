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

$adminid = $_SESSION['adminid'];
// start of post
$name = $_POST["name"];
$msg = $_POST["msg"];
// end of post

// start of data sanitize and existence check
 if (empty($name)) {
    // If input feild is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'name';
    header('Location: notiuser.php');
    exit;
} elseif(empty($msg)){
    // If input feild is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'msg';
     header('Location: notiuser.php');
    exit;
}else{
    //resets the code
     $_SESSION['exitcodev2'] = '';
}

$name = inputcleaner($name,$mysqli);
$msg = inputcleaner($msg,$mysqli);

 if ($mysqli->query("INSERT INTO `$db`.
								   `notifications` (`idnotifications`, `readyn`,
								   `content`, `date`, `fromwho`, `towho`) VALUES (NULL,
								   '0', '$msg', CURRENT_TIMESTAMP, '$adminid', '$name');")
								   === TRUE) {
								   //Will notify admins of error 
								   }else{
                                    echo "DB";
                                    exit;
                                   }
header('Location: viewnotifications.php');
?>