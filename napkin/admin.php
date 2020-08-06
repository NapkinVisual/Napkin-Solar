<!--
/*©agpl*************************************************************************
*                                                                              *
* Napkin Visual – Visualisation platform for the Napkin platform               *
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
	include_once "php/utils/getAllUsers.php";
	include_once "php/utils/getUser.php";
	include_once "php/utils/getAllEntities.php";
	include_once "php/utils/getAllLogs.php";

	$uid = null;
	$user = null;

	if(!empty($_SESSION['uid'])) {
		$uid = $_SESSION['uid'];
		$user = getUser($pdo, $uid);

		if($user['type'] != 'admin') header('Location: main');
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
		.manageEntity:hover {
			cursor: pointer;
		}
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

	<!-- entity info modal -->
	<div class="modal fade" id="entityInfoModal" tabindex="-1" role="dialog" aria-labelledby="entityInfoModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-lg modal-dialog-scrollable">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="entityInfoModalLabel">Entity info</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body container-fluid">

					<dl class="row">
					  <dd class="col-md-6">
							<dl class="row">
								<dt class="col-md-4 mb-3">Name</dt>
								<dd class="col-md-8"> <span id="entityName"></span> </dd>

								<dt class="col-md-4 mb-3">Description</dt>
								<dd class="col-md-8"> <span id="entityDescription"></span> </dd>

								<dt class="col-md-4 mb-3">Created on</dt>
								<dd class="col-md-8"> <span id="entityCreatedOn"></span> </dd>
							</dl>
						</dd>

					  <dd class="col-md-6">
					    <dl class="row">
					      <dt class="col-md-4 mb-3">Shared with:</dt>
					      <dd class="col-md-8"> <span id="entityShared"></span> </dd>
					    </dl>
					  </dd>
					</dl>

	      </div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
	    </div>
	  </div>
	</div>

	<!-- user info modal -->
	<div class="modal fade" id="userInfoModal" tabindex="-1" role="dialog" aria-labelledby="userInfoModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-lg modal-dialog-scrollable">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="userInfoModalLabel">User info</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body container-fluid">

					<dl class="row">
					  <dd class="col-md-9">
							<dl class="row">
								<dt class="col-md-4 mb-3">Username</dt>
								<dd class="col-md-8"> <span id="username"></span> </dd>

								<dt class="col-md-4 mb-3">Email</dt>
								<dd class="col-md-8"> <span id="userEmail"></span> </dd>

								<dt class="col-md-4 mb-3">Last login</dt>
								<dd class="col-md-8"> <span id="userLastLogin"></span> </dd>

								<dt class="col-md-4 mb-3">Created on</dt>
								<dd class="col-md-8"> <span id="userCreatedOn"></span> </dd>
							</dl>
						</dd>

					  <dd class="col-md-3"></dd>
					</dl>

	      </div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
	    </div>
	  </div>
	</div>

	<!-- delete user modal -->
	<div class="modal fade" id="deleteUserModal" tabindex="-1" role="dialog" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-scrollable">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="deleteUserModalLabel">Delete user</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
					<p class="lead text-justify">Are you sure you want to delete?</p>
	      </div>
				<div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
	        <button type="button" class="btn btn-danger" id="deleteUser" data-dismiss="modal" data-userid="">Delete</button>
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
							<a class="nav-link" href="stock">
								<span data-feather="box"></span>
								Stock
							</a>
						</li>
					</ul>

					<br />

        	<ul class="nav flex-column mb-2">
          	<li class="nav-item">
            	<a class="nav-link" href="account">
              	<span data-feather="user"></span>
              	Account
            	</a>
          	</li>
          	<li class="nav-item">
            	<a class="nav-link" href="log">
              	<span data-feather="file-text"></span>
              	Logs
            	</a>
          	</li>
        	</ul>

					<br />

					<ul class="nav flex-column mb-2">
						<li class="nav-item">
							<a class="nav-link active" href="admin">
								<span data-feather="user-check"></span>
								Administration <span class="sr-only">(current)</span>
							</a>
						</li>
          	<li class="nav-item">
            	<a class="nav-link" href="settings">
              	<span data-feather="settings"></span>
              	Settings
            	</a>
          	</li>
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
	        	<h1 class="h2">Administration dashboard</h1>

	        	<div class="btn-toolbar mb-2 mb-md-0">
							<button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#newEntityModal">
								<strong>Import users</strong>
								<span data-feather="plus"></span>
							</button>

							&nbsp;

							<button type="button" class="btn btn-sm btn-outline-secondary" id="share">
								<span data-feather="share"></span>
							</button>
	        	</div>
	      	</div>
      	</div>

				<div class="row pt-3 pb-2 mb-3">
					<div class="col-md-5 mb-3">
						<div class="list-group">
						  <a href="users?admin=<?php echo $uid; ?>" class="list-group-item list-group-item-action">
								Registered users
							</a>
						  <a href="entities?admin=<?php echo $uid; ?>" class="list-group-item list-group-item-action">
								All entities
							</a>
						  <a href="stock?admin=<?php echo $uid; ?>" class="list-group-item list-group-item-action">
								System stock
							</a>
						</div>
					</div>

					<div class="col-md-1"></div>

					<div class="col-md-6">
						<p class="lead">Admin logs</p>

						<div class="table-responsive" style="max-height: 40vh;">
							<table class="table table-sm mt-1">
								<tbody>
									<?php
										$stmt = $pdo->prepare("SELECT * FROM \"Log\" ORDER BY timestamp DESC");
										$stmt->execute();
										$res = $stmt->fetchAll();

										if(count($res) > 0) {
											foreach($res as $r) {
												echo "
													<tr>
														<th scope=\"row\"></th>
														<td>
															<span class=\"text-truncate text-muted\">
																".$r['type']."
															</span>
														</td>
														<td>
															<span class=\"text-truncate text-muted\">
																".$r['description']."
															</span>
														</td>
														<td>
															<span class=\"text-truncate text-muted\">
																".$r['entitytype']."
															</span>
														</td>
														<td>
															<span class=\"text-truncate text-muted\">
																".date("d M. Y, H:i", strtotime($r['timestamp']))."
															</span>
														</td>
													</tr>
												";
											}
										}else{
											echo "
												<tr>
													<th scope=\"row\"></th>
													<td>
														<span class=\"text-truncate text-muted\">No users found</span>
													</td>
													<td></td>
													<td></td>
													<td></td>
												</tr>
											";
										}
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>

				<div class="row pt-3 pb-2 mb-3">
					<div class="col-lg-9 mb-3">
						<p class="lead">Entities</p>
						<p>A list of all entities on the system</p>

						<div class="table-responsive" style="max-height: 40vh;">
							<table class="table table-sm mt-1">
								<caption>
									<a href="entities?admin=<?php echo $uid; ?>" class="text-truncate">Show all records</a>
								</caption>
								<thead>
			            <tr>
			              <th>#</th>
										<th>Name</th>
			              <th>Description</th>
			              <th>Created on</th>
										<th></th>
			            </tr>
			          </thead>
								<tbody>
									<?php
										$res = getAllEntities($pdo, $uid);

										if(count($res) > 0) {
											$c = 0;

											foreach($res as $r) {
												if($c++ > 8) break;
												echo "
													<tr id=\"entityRow\">
														<th scope=\"row\"></th>
														<td>
															<a href=\"main\">
																".$r['name']."
															</a>
														</td>
														<td>
															<span class=\"text-truncate text-muted\">
																".$r['description']."
															</span>
														</td>
														<td>
															<span class=\"text-truncate text-muted\">
																".date("d M. Y, H:i", strtotime($r['created_on']))."
															</span>
														</td>
														<td>
															<span
																data-feather=\"info\"
																class=\"manageEntity\"
																id=\"entityInfo\"
																data-entityid=\"".$r['entityid']."\"
															></span>
														</td>
													</tr>
												";
											}
										}else{
											echo "
												<tr>
													<th scope=\"row\"></th>
													<td>
														<span class=\"text-truncate text-muted\">No entities found</span>
													</td>
													<td></td>
													<td></td>
													<td></td>
												</tr>
											";
										}
									?>
								</tbody>
							</table>
						</div>
					</div>

					<div class="col-lg-3"></div>
				</div>

				<div class="row pt-3 pb-2 mb-3">

					<div class="col-lg-9 mb-3">
						<p class="lead">Users</p>
						<p>A list of all registered users</p>

						<div class="table-responsive" style="max-height: 40vh;">
							<table class="table table-sm mt-1">
								<caption>
									<a href="users?admin=<?php echo $uid; ?>" class="text-truncate">Show all records</a>
								</caption>
								<thead>
			            <tr>
			              <th>#</th>
										<th>Username</th>
			              <th>Email</th>
			              <th></th>
										<th></th>
			            </tr>
			          </thead>
								<tbody>
									<?php
										$res = getAllUsers($pdo, $uid);

										if(count($res) > 0) {
											$c = 0;

											foreach($res as $r) {
												if($c++ > 8) break;
												echo "
													<tr id=\"userRow\">
														<th scope=\"row\"></th>
														<td>".$r['username']."</td>
														<td>
															<span class=\"text-truncate text-muted\">
																".$r['email']."
															</span>
														</td>
														<td>
															<span
																data-feather=\"info\"
																class=\"manageEntity\"
																id=\"userInfo\"
																data-userid=\"".$r['userid']."\"
															></span>
														</td>
														<td>
															<span
																data-feather=\"trash\"
																class=\"manageEntity\"
																id=\"userDelete\"
																data-userid=\"".$r['userid']."\"
																data-toggle=\"modal\"
																data-target=\"#deleteUserModal\"
															></span>
														</td>
													</tr>
												";
											}
										}else{
											echo "
												<tr>
													<th scope=\"row\"></th>
													<td>
														<span class=\"text-truncate text-muted\">No users found</span>
													</td>
													<td></td>
													<td></td>
													<td></td>
												</tr>
											";
										}
									?>
								</tbody>
							</table>
						</div>
					</div>

					<div class="col-lg-3"></div>
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

	<script src="js/admin.php.js"></script>

</body>
</html>
