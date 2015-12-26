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
$errorcode = $_SESSION['errorcode'];

echo "<h1>$errorcode</h1>";
// start of user input feilds
echo'<form action="emailcustomer2.php"method="post">
  	    <fieldset>
  	           <h2>Enter the information of the account to be Updated</h2>
                     <br><label>Old Email</label>
  	            <input type="text" 
  	             name="email"/></br>
                 
                     <br><label>New Email</label>
  	            <input type="text" 
  	             name="nemail"/></br>
                 
<br><label>Telephone Number</label>
  	            <input type="tel" 
  	             name="tel"/></br>
                 
                 <br><label>Last 4 Digits of Credit Card</label>
  	            <input type="number" 
  	             name="l4"/></br>
            
  	            <br><button type="submit">
  	                Submit Info 
  	            </button></br>   
  	    </fieldset>
  	    </form>'
// end of user input feilds 
?>