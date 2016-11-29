<link rel="stylesheet" type="text/css" href="/jts/styles/jtsHeaderStyle.css">
<div class="container">

	<?php //include("../session.php"); ?>
	<div class="left">
		<img src="/jts/images/weblogo_small.jpg" alt="logo">
	</div>
	
	<div class="center">
		<h2 id="title">Job Tracker System</h2>
		
		<ul>
			<li><a href="/jts/renewal reminder">Renewal Reminder</a></li>
			<li><a href="/jts/jobs">Jobs</a></li>
			<li><a href="/jts/purchase orders">Purchase Orders</a></li>
			<li><a href="/jts/invoice">Invoice</a></li>
			<li><a href="#">Reports</a></li>
			<li class="dropdown"><a id="dropdown-setup" href="#">Setup</a>
				<div class="dropdown-content">
					<a href="/jts/configuration">Configuration</a>
					<a href="/jts/currency">Currency</a>
					<a href="/jts/customers">Customers</a>
					<a href="/jts/departments">Departments</a>
					<a href="/jts/modules">Modules</a>
					<a href="/jts/permissions">Permissions</a>
					<a href="/jts/roles">Roles</a>
					<a href="/jts/users">Users</a>
				</div></li>
			<li><a id="user" href="#" style="color: blue; text-decoration: underline;"><?php // echo $user_check; ?></a></li>
			<li><a id="logoutIMG" href="logout.php"><img id="logoutIMG" src="/jts/images/log-out.png" alt="logout" width="20px" height="20px"></a></li>
		</ul>
		
	</div>
</div>
<hr/>
<br/><br/>