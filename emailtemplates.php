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
$receiptsubject = 'Thank You for Paying Your Bill';
$receipthtml = '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Billing e.g. invoices and receipts</title>
<link href="styles.css" media="all" rel="stylesheet" type="text/css" />
</head>

<body itemscope itemtype="http://schema.org/EmailMessage">

<table class="body-wrap">
	<tr>
		<td></td>
		<td class="container" width="600">
			<div class="content">
				<table class="main" width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td class="content-wrap aligncenter">
							<table width="100%" cellpadding="0" cellspacing="0">
								<tr>
									<td class="content-block">
										<h1 class="aligncenter">$33.98 Paid</h1>
									</td>
								</tr>
								<tr>
									<td class="content-block">
										<h2 class="aligncenter">Thanks for using Acme Inc.</h2>
									</td>
								</tr>
								<tr>
									<td class="content-block aligncenter">
										<table class="invoice">
											<tr>
												<td>Lee Munroe<br>Invoice #12345<br>June 01 2014</td>
											</tr>
											<tr>
												<td>
													<table class="invoice-items" cellpadding="0" cellspacing="0">
														<tr>
															<td>Service 1</td>
															<td class="alignright">$ 19.99</td>
														</tr>
														<tr>
															<td>Service 2</td>
															<td class="alignright">$ 9.99</td>
														</tr>
														<tr>
															<td>Service 3</td>
															<td class="alignright">$ 4.00</td>
														</tr>
														<tr class="total">
															<td class="alignright" width="80%">Total</td>
															<td class="alignright">$ 33.98</td>
														</tr>
													</table>
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td class="content-block aligncenter">
										<a href="http://www.mailgun.com">View in browser</a>
									</td>
								</tr>
								<tr>
									<td class="content-block aligncenter">
										Acme Inc. 123 Van Ness, San Francisco 94102
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<div class="footer">
					<table width="100%">
						<tr>
							<td class="aligncenter content-block">Questions? Email <a href="mailto:">support@acme.inc</a></td>
						</tr>
					</table>
				</div></div>
		</td>
		<td></td>
	</tr>
</table>

</body>
</html>';

$failsubject = 'Your Payment has Failed';
$failhtml = '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Billing e.g. invoices and receipts</title>
<link href="styles.css" media="all" rel="stylesheet" type="text/css" />
</head>

<body itemscope itemtype="http://schema.org/EmailMessage">

<table class="body-wrap">
	<tr>
		<td></td>
		<td class="container" width="600">
			<div class="content">
				<table class="main" width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td class="content-wrap aligncenter">
							<table width="100%" cellpadding="0" cellspacing="0">
								<tr>
									<td class="content-block">
										<h1 class="aligncenter">$33.98 Paid</h1>
									</td>
								</tr>
								<tr>
									<td class="content-block">
										<h2 class="aligncenter">Thanks for using Acme Inc.</h2>
									</td>
								</tr>
								<tr>
									<td class="content-block aligncenter">
										<table class="invoice">
											<tr>
												<td>Lee Munroe<br>Invoice #12345<br>June 01 2014</td>
											</tr>
											<tr>
												<td>
													<table class="invoice-items" cellpadding="0" cellspacing="0">
														<tr>
															<td>Service 1</td>
															<td class="alignright">$ 19.99</td>
														</tr>
														<tr>
															<td>Service 2</td>
															<td class="alignright">$ 9.99</td>
														</tr>
														<tr>
															<td>Service 3</td>
															<td class="alignright">$ 4.00</td>
														</tr>
														<tr class="total">
															<td class="alignright" width="80%">Total</td>
															<td class="alignright">$ 33.98</td>
														</tr>
													</table>
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td class="content-block aligncenter">
										<a href="http://www.mailgun.com">View in browser</a>
									</td>
								</tr>
								<tr>
									<td class="content-block aligncenter">
										Acme Inc. 123 Van Ness, San Francisco 94102
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<div class="footer">
					<table width="100%">
						<tr>
							<td class="aligncenter content-block">Questions? Email <a href="mailto:">support@acme.inc</a></td>
						</tr>
					</table>
				</div></div>
		</td>
		<td></td>
	</tr>
</table>

</body>
</html>';

?>