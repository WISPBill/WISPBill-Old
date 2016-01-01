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
require_once('./billingcon.php');
$mysqli = new mysqli("$ip", "$username", "$password", "$db");

// start of post
$name = $_POST["name"];
$price = $_POST["price"];
$up = $_POST["up"];
$down = $_POST["down"];
// end of post

// start of data sanitize and existence check
 if (empty($name)) {
    // If name is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'name';
    header('Location: createplan.php');
    exit;
} elseif(empty($price)){
    // If price is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2']= 'price';
    header('Location: createplan.php');
    exit;
}
elseif(empty($up)){
    // If up is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'up';
    header('Location: createplan.php');
    exit;
}
elseif(empty($down)){
    // If email is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'down';
    header('Location: createplan.php');
    exit;
} else{
    // do nothing 
} // end if

$name = $mysqli->real_escape_string($name);
$price = $mysqli->real_escape_string($price);
$up = $mysqli->real_escape_string($up);
$down = $mysqli->real_escape_string($down);

// end of data sanitize and existence check
// start of cheack for exsting plan name

if ($result = $mysqli->query("SELECT * FROM `customer_plans` WHERE `name` = '$name'")) {
    if ($result->num_rows == 1){
    $_SESSION['exitcodev2'] = 'name';
    header('Location: createplan.php');
    exit;
    } elseif ($result->num_rows == 0){
        // do nothing 
    } else{
        echo'Something went wrong with the database please contact your webmaster';
        exit;
    }
    
    /* free result set */
    $result->close();
}
// end of cheack for exsting plan name
//start of data entry for system DB
if ($mysqli->query("INSERT INTO `$db`.`customer_plans`
                   (`idcustomer_plans`, `name`, `max_bandwith_up_kilo`, `max_bandwith_down_kilo`, `price`)
                   VALUES (NULL, '$name', '$up', '$down', '$price');") === TRUE) {
} else{
   echo'Something went wrong with the database please contact your webmaster';
      exit;
}
// end of data entry for system DB
// start of price convert
$pricer = $price*100;
//end of price convert 
// start of stripe entry

Stripe_Plan::create(array(
  "amount" => $pricer,
  "interval" => "month",
  "name" => "$up kilo up $down Kilo down",
  "currency" => "usd",
  "id" => "$name")
);
//end of stripe entry
header('Location: menue.php');
// end of file
?>