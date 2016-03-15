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

    $loc = $_GET['choice'];
    $name = $_GET['name'];

	$loc = inputcleaner($loc,$mysqli);
	$name = inputcleaner($name,$mysqli);
	
    if($loc == 'office'){
        
        echo "<input type='hidden' name='$name' value='$loc'>";
    }elseif($loc == 'site'){
        
         if ($result = $mysqli->query("SELECT * 
        FROM  `location` ")) {
    /* fetch associative array */

						echo '<label>Select the Site</label>';
					
                echo "<select class='form-control' name='$name' required>
				  <option value='' selected disabled>Please Select Site</option>";
    while ($row = $result->fetch_assoc()) {
        $id = $row["idlocation"];
        $name = $row["name"];
        echo"<option value=$id>$name</option>";
        }
 echo ' </select>
                </div>';
         }
    }elseif($loc == 'cus'){
        
       echo '<label>Select the Customer</label>
       <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Select</th>
				  <th>Name</th> 
				   <th>Phone</th>
				  <th>Email</th>
				 <th>Address</th>
				  <th>City</th>
                </tr>
                </thead>
                <tbody>';
				
                if ($result = $mysqli->query("SELECT * FROM `customer_info`")) {
      /* fetch associative array */
         
    while ($row = $result->fetch_assoc()) {
     $id= $row["idcustomer_info"];
     $fname= $row["fname"];
     $lname= $row["lname"];
     $tel= $row["phone"];
     $email= $row["email"];
     $add= $row["address"];
     $city= $row["city"];
	 $tel = "(".substr($tel,0,3).") ".substr($tel,3,3)."-".substr($tel,6);
     echo" <tr>
    <td><input type='radio' name='$name' value=$id unchecked></td>
    <td>$fname $lname</td> 
    <td>$tel</td>
    <td>$email</td>
    <td>$add</td> 
    <td>$city</td>
  </tr>";
    }
}

echo'
                </tbody>
                <tfoot>
                <tr>
                  <th>Select</th>
				  <th>Name</th> 
				   <th>Phone</th>
				  <th>Email</th>
				 <th>Address</th>
				  <th>City</th>
                </tr>
                </tfoot>
              </table>';
              echo '<script src="AdminLTE2/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="AdminLTE2/plugins/datatables/dataTables.bootstrap.min.js"></script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. Slimscroll is required when using the
     fixed layout. -->
<script>
  $(function () {
    $("#example1").DataTable();
    $("#example2").DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": true
    });
  });
</script>';
    }else{
        echo 'getlocation.php error';
    }

?>