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
* along with this program.  If not, see <http://www.gnu.org/licenses/>.        *
*                                                                              *
*****************************************************************************©*/
-->

<?php
	session_start();

	if(!empty($_SESSION['uid'])) {
		header('Location: /napkin/main');
	}
?>


<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<meta http-equiv="x-ua-compatible" content="ie=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no" />
  <meta name="description" content="User-friendly mapping" />
	<meta name="author" content="Napkin AS" />

	<title>Napkin – User-friendly mapping</title>

	<link rel="icon" href="assets/logo.svg" />

	<!-- Font-Awesome imports -->
  <!--link rel="stylesheet" href="lib/font-awesome/css/all.css" /-->

	<!-- Bootstrap CSS import -->
	<link rel="stylesheet" href="lib/bootstrap/bootstrap.min.css" />

	<!-- Custom styles -->
  <link rel="stylesheet" href="css/main.css" />

	<style type="text/css">
		body {
		  background-color: #29323c;
		}

		body::before {
			background: url(assets/earth.jpg) no-repeat center center fixed;
		  content: '';
		  z-index: -1;

		  width: 100%;
		  height: 100%;

		  position: absolute;

		  -webkit-background-size: cover;
		  -moz-background-size: cover;
		  -o-background-size: cover;
		  background-size: cover;

		  -webkit-filter: blur(5px);
		  -moz-filter: blur(5px);
		  -o-filter: blur(5px);
		  -ms-filter: blur(5px);
		  filter: blur(5px);
		}

		div.root {
			background-color: #29323c;
			color: #bfbfbf;
			border-radius: 3px;

			position: absolute;
			top: 50%;
			left: 50%;

			transform: translate(-50%, -50%);
			z-index: 2;
			width: 40%;
			padding: 20px;
		}

		div.footer {
			background-color: rgba(0, 0, 0, 0.3);
			color: #cccccc;
			border-radius: 3px;

			position: absolute;
			bottom: 0;
			left: 50%;

			transform: translate(-50%, 0);
			z-index: 3;
			text-align: center;
		}
		div.footer a, div.footer a:link, div.footer a:hover, div.footer a:focus, div.footer a:active {
			text-decoration: none;
			color: inherit;
		}

		@media (max-width: 768px) {
			div.root {
				width: 80%;
			}
		}
	</style>
</head>
<body>

  <div class="root">

		<div class="form-group">
			<label for="email">Email address</label>
			<input type="email" class="form-control form-control-dark" id="email" aria-describedby="email" placeholder="Enter email">
		</div>

		<div class="form-group" style="margin-bottom: 1.5rem;">
			<label for="password">Password</label>
			<input type="password" class="form-control form-control-dark" id="password" placeholder="Password">
		</div>

		<button type="button" class="btn btn-secondary" id="login">Log in</button>

  </div>

  <div class="footer">
		<small>
			© <span id="ccYear">2020</span> Copyright:
			<a href="https://napkingis.no" target="_blank">napkingis.no</a>.
			All rights reserved.
		</small>
  </div>

	<!-- jQuery - Popper.js - Bootstrap JS imports -->
  <script src="lib/jquery/jquery-3.5.1.min.js"></script>
  <script src="lib/popper/popper.min.js"></script>
  <script src="lib/bootstrap/bootstrap.min.js"></script>

	<script type="text/javascript">
    $("span#ccYear").html(new Date().getFullYear());
  </script>

	<!-- SHA256 local import -->
	<script src="lib/sha256/sha256.min.js"></script>

	<script src="js/index.php.js"></script>

</body>
</html>
