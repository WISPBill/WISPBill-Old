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
// Stripe API keys 
$secret_key = "sk_test_sjaS0S1Wj3kgWoOC6dRtYm8b";
$publishable_key = "pk_test_ifNvhGsXAuZeO3XvRIxC8b8l";
// End of Stripe API keys 
// Start of SQL config 
$username='wispbill'; 
$password='jK8Uaapf7Z2ZCa7c';
$ip='192.168.1.8';
$db='wispbillv2';
$usernamel='wispbill'; 
$passwordl='jK8Uaapf7Z2ZCa7c';
$ipl='192.168.1.8';
$dbl='librenms';
// End of SQL config
// Start of Billing Config
$nopayurl='https://192.168.1.8/billing';
$timezone = 'America/Chicago';
$rname ='danny'; //Router Username
$rpass = 'joekity'; //Router Password
$radiouname = 'ubnt';
$radiopass = 'ubnt';
$manufacturer = array("Ubiquiti Networks","Other");
$state= 'Minnesota';
$mapcenterlat ='45.38'; // Center of MAP
$mapcenterlon ='-93.67'; // Center of MAP
$mapzoom ='12'; // Intial Map zoom
$dns='8.8.8.8'; //Default DNS Server
$url = 'https://192.168.1.189/billingv2/';
// Dashbored info
$company = 'WISP Bill';
$companysite = 'https://google.com';
$rightfooter = '';
$uploadimgdir = "C:\\xampp\\htdocs\\billingv2\\img\\";
$menue = './link.php';
//End of Billing Config 
?>