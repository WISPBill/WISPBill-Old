<?php
require_once('./fileloader.php');
 $mysqli = new mysqli("$ip", "$username", "$password", "$db");
 $cpeid = '3';
if ($result5 = $mysqli->query("SELECT * FROM `notifications`
								   WHERE `readyn` = '0' and `content` = 'A Radio is offline ID of CPE AFFECTED = $cpeid'")) {
										if ($result5->num_rows == 1){
										// Error is open no need to make again
										echo "Erro";
										} elseif ($result5->num_rows == 0){
										// No open error have to make one
											if ($mysqli->query("INSERT INTO `$db`.
								   `notifications` (`idnotifications`, `readyn`,
								   `content`, `date`, `fromwho`, `towho`) VALUES (NULL,
								   '0', 'A Radio is offline ID of CPE AFFECTED = $cpeid', CURRENT_TIMESTAMP, 'system', 'all');")
								   === TRUE) {
								   //Will notify admins of error 
								   }
										}
										/* free result set */
										$result5->close();
								   }
?>