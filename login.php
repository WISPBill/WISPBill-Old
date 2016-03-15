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
require_once('./fileloader.php');
$mysqli = new mysqli("$ip", "$username", "$password", "$db");

// start of https force 
if($_SERVER["HTTPS"] != "on")
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}
// end of https force
//start of post
$user = $_POST["user"];
$pass = $_POST["pass"];
$epass = $_POST["epass"];
// end of post
// start of data sanitize and existence check
 if (empty($user)) {
    // If username is empty it goes back to the fourm and informs the user
    session_start(); 
    $_SESSION['exitcode'] = 'user empty';
    header('Location: index.php');
    exit;
} elseif(empty($pass)){
    // If password is empty it goes back to the fourm and informs the user
    session_start(); 
    $_SESSION['exitcode'] = 'password empty';
    header('Location: index.php');
    exit;
} elseif(empty($epass)){
    // If password is empty it goes back to the fourm and informs the user
	if($emailreader == true){
    session_start(); 
    $_SESSION['exitcode'] = 'password empty';
    header('Location: index.php');
    exit;
	}else{
		//email is not on
	}
}else{
    // do nothing 
} // end if

$user = inputcleaner($user,$mysqli);
$pass= inputcleaner($pass,$mysqli);

if($emailreader == true){
$epass = inputcleaner($epass,$mysqli);
}else{
	//email is not on
}
// end of data sanitize and existence check
// start of cheack for exsting username and or email 

if ($result = $mysqli->query("SELECT * FROM `admin_users` WHERE `username` = '$user'")) {
    if ($result->num_rows == 1){
        // Username Found
        $store='username';
    } elseif ($result->num_rows == 0){
        // Username not found will now look for email match
        if ($resulte = $mysqli->query("SELECT * FROM `admin_users` WHERE `email` = '$user'")) {
    if ($resulte->num_rows == 1){
        // Email Found
        $store='email';
    } elseif ($resulte->num_rows == 0){
        // No Email or Username Found
        session_start(); 
        $_SESSION['exitcode'] = 'no username';
        header('Location: index.php');
        exit;
    } else{
        echo'Something went wrong with the database please contact your webmaster';
        exit;
    }
    
    /* free result set */
    $resulte->close();
}
    } else{
        echo'Something went wrong with the database please contact your webmaster';
        exit;
    }
    
    /* free result set */
    $result->close();
}

// end of cheack for exsting username and or email
// start of password
//look for user or email and then compare passwords
    if ($result = $mysqli->query("SELECT * FROM `admin_users` WHERE `$store` = '$user'")) {
      /* fetch associative array */
    while ($row = $result->fetch_assoc()) {
        $hash = $row["password"];
        $priv = $row["privilege"];
        $id = $row["idadmin"];
    }
    
    /* free result set */
    $result->close();
}
// Email Stuff
if($emailreader == true){
$bytes = openssl_random_pseudo_bytes(32, $cstrong);
        if($cstrong == TRUE){
          $size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, 'ofb');
          $iv = mcrypt_create_iv($size, MCRYPT_DEV_URANDOM);
      $epassword = mcrypt_encrypt (MCRYPT_BLOWFISH,"$bytes", "$epass","ofb","$iv");
	  session_start();
    session_regenerate_id();
	  $_SESSION['iv'] = "$iv";
	  $_SESSION['key'] = "$bytes";
	  $_SESSION['emailpass'] = "$epassword";
        }else{
          echo "Openssl wont work on this server";
          exit;
        }
}else{
	//Email is not on
}
//End of Email Stuff
if (password_verify($pass, $hash)) {
    // Password is valid
    session_start();
    session_regenerate_id();
        $_SESSION['priv'] = "$priv";
        $_SESSION['adminid'] = "$id";
        $_SESSION['logged'] = "yes";
        header('Location: menue.php');
        exit;
    
} else {
    // Invalid password
    session_start(); 
        $_SESSION['exitcode'] = 'no username';
        header('Location: index.php');
        exit;
}
?>