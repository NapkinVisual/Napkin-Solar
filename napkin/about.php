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
		.list-item {
			margin-bottom: 15px;
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
            	<a class="nav-link" href="log">
              	<span data-feather="file-text"></span>
              	Logs
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
            	<a class="nav-link active" href="about">
              	<span data-feather="info"></span>
              	About <span class="sr-only">(current)</span>
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
	        	<h1 class="h2">About Napkin Visual</h1>

	        	<div class="btn-toolbar mb-2 mb-md-0">
							<button type="button" class="btn btn-sm btn-outline-secondary" id="share">
								<span data-feather="share"></span>
							</button>
	        	</div>
	      	</div>
      	</div>

				<div class="row pt-3 pb-2 mb-3">
					<div class="col">
						<div class="row">
							<div class="col">
								<p class="lead">
									Napkin Visual is a powerful and intuitive spatial analysis for the Napkin platform.
								</p>
								<p> <a href="https://napkingis.no/visual/" target="_blank">Learn more</a> </p>
							</div>
						</div>

						<hr />
						<br />

						<div class="row">
							<div class="col-md-7 col-sm-12">
								<p class="lead text-center">© Napkin AS</p>
			          <p class="text-center">
			            Copyright and licence notices for the Napkin Visual product.
			          </p>

								<br />

								<p>
									Copyright (c) 2020, Napkin AS <br />
			            All rights reserved.
								</p>
								<p>
									Napkin Visual is covered under the GNU AFFERO GENERAL PUBLIC LICENSE. <br />
									LICENSE: <a href="https://github.com/NapkinGIS/Napkin-Visual/blob/master/LICENCE" target="_blank">https://github.com/NapkinGIS/Napkin-Visual/blob/master/LICENCE</a>
								</p>
							</div>

							<div class="col-md-5 col-sm-12">
								<p>Disclosure, technologies used:</p>
								<ul>
								  <li class="list-item">
										Deck.gl, <a href="https://github.com/visgl/deck.gl" target="_blank">https://github.com/visgl/deck.gl</a> <br />
										Copyright (c) 2015 - 2017 Uber Technologies, Inc.
									</li>
								  <li class="list-item">
										Mapbox, <a href="https://github.com/mapbox/mapbox-gl-js" target="_blank">https://github.com/mapbox/mapbox-gl-js</a> <br />
										Copyright (c) 2020, Mapbox
									</li>
								  <li class="list-item">
										Bootstrap, <a href="https://github.com/twbs/bootstrap" target="_blank">https://github.com/twbs/bootstrap</a> <br />
										Copyright (c) 2011–2020, Bootstrap Authors and Twitter, Inc.
									</li>
									<li class="list-item">
										Kepler.gl, <a href="https://github.com/keplergl/kepler.gl" target="_blank">https://github.com/keplergl/kepler.gl</a> <br />
										Copyright (c) 2018 Uber Technologies, Inc.
									</li>
								</ul>
							</div>
						</div>

						<hr />
						<br />

						<h1 class="h4">System information</h1>

						<br />

						<div class="row">
							<div class="col-md-6 col-sm-12">
								<dl class="row">
									<dt class="col-sm-4">System architecture</dt>
								  <dd class="col-sm-8">
										<?php
											$architecture = php_uname('m');

											echo $architecture;
										?>
									</dd>

								  <dt class="col-sm-4">CPU cores</dt>
								  <dd class="col-sm-8">
										<?php
											if(function_exists('exec')) {
												$www_total_count = 0;
												$www_unique_count = 0;
												$unique = [];
												@exec('netstat -an | egrep \':80|:443\' | awk \'{print $5}\' | grep -v \':::\*\' |  grep -v \'0.0.0.0\'', $results);

												foreach($results as $result) {
													$array = explode(':', $result);
													$www_total_count++;

													if(preg_match('/^::/', $result)) $ipaddr = $array[3];
													else $ipaddr = $array[0];

													if(!in_array($ipaddr, $unique)) {
														$unique[] = $ipaddr;
														$www_unique_count++;
													}
												}

												unset($results);

												$coreCount = count($unique);

												echo $coreCount;
											}
										?>
									</dd>

									<dt class="col-sm-4">System load</dt>
								  <dd class="col-sm-8">
										<?php
											$interval = 1;
											$rs = sys_getloadavg();
											$interval = $interval >= 1 && 3 <= $interval ? $interval : 1;
											$load = $rs[$interval];
											echo round(($load * 100) / $coreCount, 2);
										?>
									</dd>

									<dt class="col-sm-4">Memory usage</dt>
								  <dd class="col-sm-8">
										<?php
											$free = shell_exec('free');
											$free = (string)trim($free);
											$free_arr = explode("\n", $free);
											$mem = explode(" ", $free_arr[1]);
											$mem = array_filter($mem);
											$mem = array_merge($mem);
											$memory_usage = $mem[2] / $mem[1] * 100;

											echo $memory_usage;
										?>
									</dd>

									<dt class="col-sm-4">Current disk usage</dt>
								  <dd class="col-sm-8">
										<?php
											$disktotal = disk_total_space ('/');
											$diskfree  = disk_free_space  ('/');
											$diskuse   = round (100 - (($diskfree / $disktotal) * 100)) .'%';

											echo $diskuse;
										?>
									</dd>
								</dl>
							</div>

							<div class="col-md-6 col-sm-12">
								<dl class="row">
								  <dt class="col-sm-4">Server uptime</dt>
								  <dd class="col-sm-8">
										<?php
											//$uptime = floor(preg_replace('/\.[0-9]+/', '', file_get_contents('/proc/uptime')) / 86400);
											$uptime = file_get_contents('/proc/uptime');
											echo $uptime;
										?>
									</dd>

									<dt class="col-sm-4">Hostname</dt>
								  <dd class="col-sm-8">
										<?php
											$hostname = php_uname('n');

											echo $hostname;
										?>
									</dd>

									<dt class="col-sm-4">OS</dt>
								  <dd class="col-sm-8">
										<?php
											$OS = php_uname('s');

											echo $OS;
										?>
									</dd>

									<dt class="col-sm-4">Kernel version</dt>
								  <dd class="col-sm-8">
										<?php
											$kernel = explode(' ', file_get_contents('/proc/version'));
											$kernel = $kernel[2];

											echo $kernel;
										?>
									</dd>
								</dl>
							</div>
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

	<script src="js/about.php.js"></script>

</body>
</html>
