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
require_once('./billconfig.php');
// Needed to Geocode
require_once('./geocoder.php');
// Airos Stat graber and DHCP ip getter
require_once('./airosstatpoller.php');
// Noti Header Data grab
require_once('./notihead.php');
// Email Count
require_once('./unreademail.php');
set_include_path(get_include_path() . PATH_SEPARATOR . 'ssh');
include('Net/SSH2.php');

?>