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
	include_once "php/utils/getAllEntities.php";

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

	<!-- Leaflet CSS import -->
	<link rel="stylesheet" href="lib/leaflet/leaflet.css" />

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

	<!-- new entity modal -->
	<div class="modal fade" id="newEntityModal" tabindex="-1" role="dialog" aria-labelledby="newEntityModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-lg modal-dialog-scrollable">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="newEntityModalLabel">Create new entity</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
				<form id="createEntityForm" style="overflow-y: auto;">
		      <div class="modal-body">

						<div class="row">
							<div class="col-md-9">
								<div class="form-group">
							    <label for="entityName">Entity name</label>
							    <input type="text" class="form-control" id="entityName" aria-describedby="entityName" required />
							  </div>
								<div class="form-group">
							    <label for="entityDescription">Entity description</label>
							    <textarea class="form-control" id="entityDescription" rows="4"></textarea>
							  </div>
							</div>
							<div class="col-md-3"></div>
						</div>

						<br />
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
		        <button type="submit" class="btn btn-primary">Create</button>
		      </div>
				</form>
	    </div>
	  </div>
	</div>

	<!-- info modal -->
	<div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-lg modal-dialog-scrollable">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="infoModalLabel">Entity info</h5>
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

	<!-- share entity modal -->
	<div class="modal fade" id="shareEntityModal" tabindex="-1" role="dialog" aria-labelledby="shareEntityModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-scrollable">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="shareEntityModalLabel">Share entity</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
				<form id="shareEntityForm" data-entityid="" style="overflow-y: auto;">
		      <div class="modal-body">

						<div class="row">
							<div class="col">
								<div class="form-group">
							    <label for="shareName">Username</label>
							    <input type="text" class="form-control" id="shareName" aria-describedby="shareName" required />
							  </div>
							</div>
						</div>

						<div class="row">
							<div class="col">
								<div class="list-group" id="userList">
									<button type="button" class="list-group-item list-group-item-action" disabled>No entities found</button>
								</div>
							</div>
						</div>

						<br />

		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
		      </div>
				</form>
	    </div>
	  </div>
	</div>

	<!-- edit entity modal -->
	<div class="modal fade" id="editEntityModal" tabindex="-1" role="dialog" aria-labelledby="editEntityModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-lg modal-dialog-scrollable">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="editEntityModalLabel">Edit entity</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
				<form id="editEntityForm" style="overflow-y: auto;">
		      <div class="modal-body">

						<div class="row">
							<div class="col-md-9">
								<div class="form-group">
							    <label for="entityName">Entity name</label>
							    <input type="text" class="form-control" id="entityName" aria-describedby="entityName" required />
							  </div>
								<div class="form-group">
							    <label for="entityDescription">Entity description</label>
							    <textarea class="form-control" id="entityDescription" rows="4"></textarea>
							  </div>
							</div>
							<div class="col-md-3"></div>
						</div>
						<br />

		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
		        <button type="submit" class="btn btn-primary" id="editEntity" data-entityid="">Save</button>
		      </div>
				</form>
	    </div>
	  </div>
	</div>

	<!-- delete entity modal -->
	<div class="modal fade" id="deleteEntityModal" tabindex="-1" role="dialog" aria-labelledby="deleteEntityModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-scrollable">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="deleteEntityModalLabel">Delete entity</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
					<p class="lead text-justify">Are you sure you want to delete?</p>
	      </div>
				<div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
	        <button type="button" class="btn btn-danger" id="deleteEntity" data-dismiss="modal" data-entityid="">Delete</button>
	      </div>
	    </div>
	  </div>
	</div>




	<nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
		<a class="navbar-brand col-md-1 mr-0 px-3" href="main">
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
			<nav id="sidebarMenu" class="col d-md-block bg-light sidebar collapse">
				<div class="sidebar-sticky pt-3">
					<ul class="nav flex-column">
						<li class="nav-item">
							<a class="nav-link" href="main">
								<span data-feather="map"></span>
								<span class="d-md-none"> Home </span>
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link active" href="entities">
								<span data-feather="folder"></span>
								<span class="d-md-none"> Entities <span class="sr-only">(current)</span> </span>
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="#">
								<span data-feather="box"></span>
								<span class="d-md-none"> Stock </span>
							</a>
						</li>
					</ul>

					<br />

        	<ul class="nav flex-column mb-2">
          	<li class="nav-item">
            	<a class="nav-link" href="#">
              	<span data-feather="user"></span>
              	<span class="d-md-none"> Account </span>
            	</a>
          	</li>
          	<li class="nav-item">
            	<a class="nav-link" href="log">
              	<span data-feather="file-text"></span>
              	<span class="d-md-none"> Logs </span>
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
											<span class=\"d-md-none\"> Administration </span>
										</a>
									</li>

									<li class=\"nav-item\">
										<a class=\"nav-link\" href=\"#\">
											<span data-feather=\"settings\"></span>
											<span class=\"d-md-none\"> Settings </span>
										</a>
									</li>
								";
							}
						?>
          	<li class="nav-item">
            	<a class="nav-link" href="about">
              	<span data-feather="info"></span>
              	<span class="d-md-none"> About </span>
            	</a>
          	</li>
        	</ul>

					<br />

					<p class="text-muted text-center d-md-none">
						<small>
							© <span id="ccYear">2020</span>
							<a href="https://napkingis.no" target="_blank">napkingis.no</a>.
							All rights reserved.
						</small>
					</p>
      	</div>
    	</nav>

    	<main role="main" class="col" id="mainPanel">
      	<div class="row">
					<div class="col d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
	        	<h1 class="h2">Entities</h1>

	        	<div class="btn-toolbar mb-2 mb-md-0" role="toolbar">
							<button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#newEntityModal">
								<strong>New entity</strong>
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
					<div class="col">
						<form class="form-inline" method="GET" action="">
							<input type="hidden" name="op" value="search" />

							<input type="text" class="form-control form-control-sm mb-2 mr-sm-2" name="name" placeholder="Name" />

							<input type="text" class="form-control form-control-sm mb-2 mr-sm-2" name="createdFrom" id="createdFrom" placeholder="From" />
							<input type="text" class="form-control form-control-sm mb-2 mr-sm-2" name="createdTo" id="createdTo" placeholder="To" />

							<button type="button" class="btn btn-sm btn-outline-secondary mb-2">Search</button>
						</form>

						<br />

						<div class="table-responsive">
							<table class="table table-striped table-sm">
								<thead>
									<tr>
										<th>#</th>
										<th>Name</th>
										<th>Description</th>
										<th>Created on</th>
										<th></th>
										<th></th>
										<th></th>
										<th></th>
									</tr>
								</thead>
								<tbody id="entityTable">
									<?php
										$res = getAllEntities($pdo, $uid);

										if(count($res) > 0) {
											foreach($res as $r) {
												echo "
													<tr id=\"entityRow\" data-entityid=\"".$r['entityid']."\">
														<th scope=\"row\"></th>
														<td>
															<span class=\"text-truncate\">
																".$r['name']."
															</span>
														</td>
														<td>
															<span class=\"text-truncate\">
																".$r['description']."
															</span>
														</td>
														<td>
															<span class=\"text-truncate\">
																".date("d M. Y, H:i", strtotime($r['created_on']))."
															</span>
														</td>
														<td>
															<span
																data-feather=\"info\"
																class=\"manageEntity\"
																id=\"infoEntity\"
																data-entityid=\"".$r['entityid']."\"
															></span>
														</td>
														<td>
															<span
																data-feather=\"share-2\"
																class=\"manageEntity\"
																id=\"shareEntity\"
																data-entityid=\"".$r['entityid']."\"
																data-toggle=\"modal\"
																data-target=\"#shareEntityModal\"
															></span>
														</td>
														<td>
															<span
																data-feather=\"edit-2\"
																class=\"manageEntity\"
																id=\"editEntity\"
																data-entityid=\"".$r['entityid']."\"
																data-toggle=\"modal\"
																data-target=\"#editEntityModal\"
															></span>
														</td>
														<td>
															<span
																data-feather=\"trash\"
																class=\"manageEntity\"
																id=\"deleteEntity\"
																data-entityid=\"".$r['entityid']."\"
																data-toggle=\"modal\"
																data-target=\"#deleteEntityModal\"
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
														<span class=\"text-truncate text-muted\">
															No entities found
														</span>
													</td>
													<td></td> <td></td> <td></td> <td></td> <td></td>
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

	<script src="js/entities.php.js"></script>

</body>
</html>
