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
$adminid = $_SESSION['adminid'];
$mysqli = new mysqli("$ip", "$username", "$password", "$db");

// start of post
$note = $_POST["text1"];
$id = $_POST["contact"];

$id = inputcleaner($id,$mysqli);
$note = inputcleaner($note,$mysqli);

// end of post
// start of data sanitize and existence check
 if (empty($note)) {
    // If note is empty it goes back to the fourm and informs the user
    $_SESSION['errorcode'] = 'note';
    header('Location: createcontactnote.php');
    exit;
} elseif(empty($id)){
    // If id is empty it goes back to the fourm and informs the user
    $_SESSION['errorcode'] = 'con';
    header('Location: createcontactnote.php');
    exit;
} else{
    // do nothing 
} // end if

$note = $mysqli->real_escape_string($note);
$id = $mysqli->real_escape_string($id);

// end of data sanitize and existence check


if ($result = $mysqli->query("INSERT INTO `$db`.`contact_notes`
                             (`idcontact_notes`, `note`, `date`,
                             `contacts_idcontacts`, `admin_users_idadmin`)
                             VALUES (NULL, '$note', CURRENT_TIMESTAMP, '$id', '$adminid');")) {
    /* fetch associative array */
   

} else {
   echo 'DB ERROR';
  exit;
}// end if
header('Location: index.php');
?>