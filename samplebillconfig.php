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
$secret_key = "";
$publishable_key = "";
// End of Stripe API keys 
// Start of SQL config 
$username=''; 
$password='';
$ip='';
$db='';
//only needed if you want to inergrate with librenms
$usernamel=''; 
$passwordl='';
$ipl='';
$dbl='';
//Freeradius
$usernamer='';
$passwordr='';
$ipr='';
$dbr='';
$nopayurl=''; // For WISPr-Redirection-URL
// End of SQL config
// Start of Billing Config
// These features are being worked on
$nopayportalip='192.168.4.2'; // IP in tunnel for captive portal should be lowest in subnet max tunnesl 200
$nopaytunip='192.168.1.3'; // IP for tunnel endpoint
// Timezone
date_default_timezone_set('America/Chicago');
//deprecated not sure if any other refrences
$rname =''; //Router Username
$rpass = ''; //Router Password
// Needed for AIROS Polling
$radiouname = 'ubnt';
$radiopass = 'ubnt';
// Array for inventory
$manufacturer = array("Ubiquiti Networks","Other");
// US Sate
$state= '';
$mapcenterlat ='xx.xx'; // Center of MAP 
$mapcenterlon ='xx.xx'; // Center of MAP
$mapzoom ='12'; // Intial Map zoom
$dns='8.8.8.8'; //Default DNS Server
$url = ''; // Server URL https://example.com
// Dashbored info
$company = 'WISP Bill';
$companysite = 'https://google.com';
$rightfooter = '';
$uploadimgdir = "/home/danny/wispbillv2/img/"; // Where to put images 
$menue = './link.php';
$sysimg = "$urlimg/sys.jpg";
$unkownimg = "$url/img/unk.jpg";
$delfolder = '[Gmail]/All Mail'; // Folder for deleted emails to go to
$emailreader = false;// False if you don't want email clent
//End of Billing Config
// Dashbored NOTI Cleanup only needed in DEV Envrioment
$notitimestamp=  time();
$source = '';
$notimesg = '';
$calevents = '1';
// This is the key for Device credential encryption
$masterkey = 'makeitgood';
//Sendgrid Config
$sendgrid = new SendGrid('YOUR_SENDGRID_APIKEY');
$sendgridon = true; 
$fromemail = '';
?>