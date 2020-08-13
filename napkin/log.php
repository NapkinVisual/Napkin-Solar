<!--
/*©agpl*************************************************************************
*                                                                              *
* Napkin Solar – Napkin-Visual based analysis tool for the solar energy market *
* Copyright (C) 2020  Napkin AS                                                *
*                                                                              *
* This program is free software: you can redistribute it and/or modify         *
* it under the terms of the GNU Affero General Public License as published by  *
* the Free Software Foundation, either version 3 of the License, or            *
* (at your option) any later version.                                          *
*                                                                              *
* This program is distributed in the hope that it will be useful,              *
* but WITHOUT ANY WARRANTY; without even the implied warranty of               *
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the                 *
* GNU Affero General Public License for more details.                          *
*                                                                              *
* You should have received a copy of the GNU Affero General Public License     *
* along with this program. If not, see <http://www.gnu.org/licenses/>.         *
*                                                                              *
*****************************************************************************©*/
-->

<?php
	//error_reporting(E_ALL); ini_set('display_errors',1); ini_set('error_reporting', E_ALL); ini_set('display_startup_errors',1); error_reporting(-1);

	session_start();

	include "php/utils/init_db.php";
	include_once "php/utils/getUser.php";
	include_once "php/utils/searchLog.php";
	include_once "php/utils/getAllLogs.php";

	$uid = null;
	$user = null;

	if(!empty($_SESSION['uid'])) {
		$uid = $_SESSION['uid'];
		$user = getUser($pdo, $uid);
	}else{
		header('Location: /napkin');
	}
?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta http-equiv="x-ua-compatible" content="ie=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, shrink-to-fit=no, user-scalable=no" />
  <meta name="description" content="User-friendly mapping" />
	<meta name="author" content="Napkin AS" />

	<title>Napkin – User-friendly mapping</title>

	<link rel="icon" href="assets/logo.svg" />

	<!-- Bootstrap CSS import -->
	<link rel="stylesheet" href="lib/bootstrap/bootstrap.min.css" />

	<!-- Bootstrap-datepicker CSS import -->
	<link rel="stylesheet" href="lib/bootstrap-datepicker/bootstrap-datepicker.min.css" />

	<!-- Custom styles -->
  <link rel="stylesheet" href="css/main.css" />
	<link rel="stylesheet" href="css/view.css" />

	<style type="text/css">
		/**/
	</style>

</head>
<body>

	<!-- loading modal -->
	<div class="modal fade" id="loadingModal" tabindex="-1" role="dialog" aria-labelledby="loadingModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-scrollable">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="loadingModalLabel">Loading...</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">

					<div class="spinner-border text-primary" role="status">
						<span class="sr-only">Loading...</span>
					</div>

	      </div>
	    </div>
	  </div>
	</div>




	<nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
		<a class="navbar-brand col-md-3 col-lg-2 mr-0 px-3" href="main">
			<img src="assets/logo.svg" width="30" height="30" class="d-inline-block align-top" alt="Napkin logo" />
		</a>

		<button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-toggle="collapse" data-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<input class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search" />

		<ul class="navbar-nav px-3">
			<li class="nav-item text-nowrap">
				<a class="nav-link" href="logout">Sign out</a>
			</li>
		</ul>
	</nav>

	<div class="container-fluid">
		<div class="row">
			<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
				<div class="sidebar-sticky pt-3">
					<ul class="nav flex-column">
						<li class="nav-item">
							<a class="nav-link" href="main">
								<span data-feather="map"></span>
								Home
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="entities">
								<span data-feather="folder"></span>
								Entities
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="#">
								<span data-feather="box"></span>
								Stock
							</a>
						</li>
					</ul>

					<br />

        	<ul class="nav flex-column mb-2">
          	<li class="nav-item">
            	<a class="nav-link" href="#">
              	<span data-feather="user"></span>
              	Account
            	</a>
          	</li>
          	<li class="nav-item">
            	<a class="nav-link active" href="log">
              	<span data-feather="file-text"></span>
              	Logs <span class="sr-only">(current)</span>
            	</a>
          	</li>
        	</ul>

					<br />

					<ul class="nav flex-column mb-2">
						<?php
							if($user['type'] == 'admin') {
								echo "
									<li class=\"nav-item\">
										<a class=\"nav-link\" href=\"#\">
											<span data-feather=\"user-check\"></span>
											Administration
										</a>
									</li>

									<li class=\"nav-item\">
			            	<a class=\"nav-link\" href=\"#\">
			              	<span data-feather=\"settings\"></span>
			              	Settings
			            	</a>
			          	</li>
								";
							}
						?>
          	<li class="nav-item">
            	<a class="nav-link" href="about">
              	<span data-feather="info"></span>
              	About
            	</a>
          	</li>
        	</ul>

					<br />

					<p class="text-muted text-center">
						<small>
							© <span id="ccYear">2020</span>
							<a href="https://napkingis.no" target="_blank">napkingis.no</a>.
							All rights reserved.
						</small>
					</p>
      	</div>
    	</nav>

    	<main role="main" class="col-md-9 col-lg-10 ml-sm-auto px-md-4">
      	<div class="row">
					<div class="col d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
	        	<h1 class="h2">Activity logs</h1>

	        	<div class="btn-toolbar mb-2 mb-md-0">
							<button type="button" class="btn btn-sm btn-outline-secondary" id="share">
								<span data-feather="share"></span>
							</button>
	        	</div>
	      	</div>
      	</div>

				<div class="row pt-3 pb-2 mb-3">
					<div class="col">
						<form class="form-inline" method="GET" action="">
							<input type="hidden" name="op" value="search" />

							<input type="text" class="form-control form-control-sm mb-2 mr-sm-2" name="dateFrom" id="dateFrom" placeholder="From" />
							<input type="text" class="form-control form-control-sm mb-2 mr-sm-2" name="dateTo" id="dateTo" placeholder="To" />

							<select class="form-control form-control-sm custom-select custom-select-sm mb-2 mr-sm-2" name="type">
								<option value="" selected disabled hidden>Choose type</option>
								<option value="create">create</option>
								<option value="update">update</option>
								<option value="rename">rename</option>
								<option value="move">move</option>
								<option value="delete">delete</option>
							</select>

							<button type="submit" class="btn btn-sm btn-outline-secondary mb-2">Search</button>
						</form>

						<br />

						<div class="table-responsive">
							<table class="table table-striped table-sm">
								<thead>
									<tr>
										<th>#</th>
										<th>Type</th>
			              <th>Description</th>
			              <th>Entity</th>
										<th>Timestamp</th>
									</tr>
								</thead>
								<tbody>
									<?php
										$res = getAllLogs($pdo, $uid);

										if(count($res) > 0) {
											foreach($res as $r) {
												echo "
													<tr>
														<th scope=\"row\"></th>
														<td><span class=\"text-truncate\">".$r['type']."</span></td>
														<td><span class=\"text-truncate\">".$r['description']."</span></td>
														<td><span class=\"text-truncate\">".$r['entitytype']."</span></td>
														<td><span class=\"text-truncate\">".date("d M. Y, H:i", strtotime($r['timestamp']))."</span></td>
													</tr>
												";
											}
										}else{
											echo "
												<tr>
													<th scope=\"row\"></th>
													<td><span class=\"text-truncate text-muted\">No records found</span></td>
													<td></td> <td></td> <td></td>
												</tr>
											";
										}
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
    	</main>
  	</div>
	</div>

	<!-- jQuery - Popper.js - Bootstrap JS imports -->
  <script src="lib/jquery/jquery-3.5.1.min.js"></script>
  <script src="lib/popper/popper.min.js"></script>
  <script src="lib/bootstrap/bootstrap.min.js"></script>

	<script src="lib/feather/feather.min.js"></script>
	<script src="lib/chart/chart.min.js"></script>
	<script src="lib/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>

	<script type="text/javascript">
    $("span#ccYear").html(new Date().getFullYear());
  </script>

	<script src="js/index.js"></script>

	<script src="js/log.php.js"></script>

</body>
</html>
