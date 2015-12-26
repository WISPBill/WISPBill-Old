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
$plan = $_SESSION['plan'];
$email = $_SESSION['email'];
$mysqli = new mysqli("$ip", "$username", "$password", "$db");
// start of flow
$flow = $_SESSION['flow'];
if($flow == 3){
    // do nothing
}else{
    // user tried to skip a step
    header('Location: createcustomer.php');
}
// Plan cost capture
  if ($result = $mysqli->query("SELECT * FROM `customer_plans` WHERE `idcustomer_plans` = '$plan'")) {
    /* fetch associative array */
    while ($row = $result->fetch_assoc()) {
        $price = $row["price"];
        }

    /* free result set */
    $result->close();
}
$price = $price*100;
// Strip API code
require_once('billingcon.php'); ?>

<form action="createcustomer6.php" method="post">
  <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
          data-key="<?php echo $stripe['publishable_key']; ?>"
          data-email="<?php echo $email;?>"
          data-zip-code="true"
          data-amount="<?php echo $price;?>" data-description="Setup Autopay"></script>
</form>

