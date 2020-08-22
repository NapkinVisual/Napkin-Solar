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

	<!-- Uber Font -->
  <link rel="stylesheet" href="lib/visual/superfine.css" />

  <!-- MapBox css -->
  <link href="lib/visual/mapbox-gl.css" rel="stylesheet" />

  <!-- Load React/Redux -->
  <script type="text/javascript" src="lib/visual/react.production.min.js"></script>
  <script type="text/javascript" src="lib/visual/react-dom.production.min.js"></script>
  <script type="text/javascript" src="lib/visual/redux.js"></script>
  <script type="text/javascript" src="lib/visual/react-redux.min.js"></script>
  <script type="text/javascript" src="lib/visual/styled-components.min.js"></script>

  <!-- Load build -->
  <script type="text/javascript" src="lib/visual/build.min.js"></script>

	<!-- Custom styles -->
  <link rel="stylesheet" href="css/main.css" />
	<link rel="stylesheet" href="css/view.css" />

	<style type="text/css">
		#app p {
			margin-top: 0;
			margin-bottom: 0;
		}

		#app tr.row {
			margin-left: 0;
			margin-right: 0;
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
							<a class="nav-link active" href="main">
								<span data-feather="map"></span>
								<span class="d-md-none"> Home <span class="sr-only">(current)</span> </span>
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="#">
								<span data-feather="folder"></span>
								<span class="d-md-none"> Entities </span>
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
					<!--h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
						<span>Saved reports</span>
						<a class="d-flex align-items-center text-muted" href="#" aria-label="Add a new report">
							<span data-feather="plus-circle"></span>
						</a>
					</h6-->

        	<ul class="nav flex-column mb-2">
          	<li class="nav-item">
            	<a class="nav-link" href="#">
              	<span data-feather="user"></span>
              	<span class="d-md-none"> Account </span>
            	</a>
          	</li>
          	<li class="nav-item">
            	<a class="nav-link" href="#">
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
            	<a class="nav-link" href="#">
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
					<div class="col" id="app" style="padding: 0;"></div>
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

	<script type="text/javascript">
    $("span#ccYear").html(new Date().getFullYear());
  </script>

	<script type="text/javascript">
		"use strict";

		function calcWidth(width) {
			let W = window.innerWidth;

			if(W > 767)
				return width - 50;
			else
			if(W > 575)
				return width;

			return width;
		}

		function calcHeight(height) {
			let W = window.innerWidth;

			if(W > 767)
				return height - 54;
			else
			if(W > 575)
				return height - 129;

			return height - 129;
		}



		/* MapBox token */
	  const MAPBOX_TOKEN = "pk.eyJ1IjoiYW5kcmVhc2F0YWthbiIsImEiOiJjazlndzM1cmUwMnl5M21tZjQ3dXpzeHJnIn0.oE5zp040ZzJj5QgCDznweg";

	  /*
	    Potential parks:
	      Pidhorodnye Solar Park:   48.606888, 35.150520
	      Starokozache Solar Park:  46.466667, 30.733333
	      Perovo Solar Park:        44.900000, 33.933333
	      Okhotnykovo Solar Park:   45.238889, 33.592778
	  */

	  /** STORE **/
	  const reducers = (function createReducers(redux, keplerGl) {
	    return redux.combineReducers({
	      keplerGl: keplerGl.keplerGlReducer.initialState({
	        mapState: { // norge – lat:60 lng:9 zoom:5
	          latitude: 48.606888,
	          longitude: 35.150520,
	          zoom: 15
	        },
	        mapStyle: {
	          styleType: "satellite",
	          threeDBuildingColor: [ 192, 192, 192 ]
	        },
	        uiState: {
	          currentModal: null,
						activeSidePanel: false,
	          readOnly: false
	        }
	      })
	    });
	  }(Redux, KeplerGl));

	  const middleWares = (function createMiddlewares(keplerGl) {
	    return keplerGl.enhanceReduxMiddleware([
	      // Add other middlewares here
	    ]);
	  }(KeplerGl));

	  const enhancers = (function craeteEnhancers(redux, middles) {
	    return redux.applyMiddleware(...middles);
	  }(Redux, middleWares));

	  const store = (function createStore(redux, enhancers) {
	    const initialState = {};

	    return redux.createStore(
	      reducers,
	      initialState,
	      redux.compose(enhancers)
	    );
	  }(Redux, enhancers));
	  /** END STORE **/

	  /** COMPONENTS **/
	  const KeplerElement = (function(react, keplerGl, mapboxToken) {
	    return function(props) {
	      let rootElm = react.useRef(null);

	      let _useState = react.useState({
	        width: calcWidth(window.innerWidth),
	        height: calcHeight(window.innerHeight)
	      });

	      let windowDimension = _useState[0],
	          setDimension = _useState[1];

	      react.useEffect(function sideEffect() {
	        function handleResize() {
	          setDimension({
	            width: calcWidth(window.innerWidth),
	            height: calcHeight(window.innerHeight)
	          });
	        };
	        window.addEventListener("resize", handleResize);
	        return function() { window.removeEventListener("resize", handleResize); };
	      }, []);

	      return react.createElement(
	        "div",
	        { style: { /*position: "absolute", left: 0,*/ width: "100%", height: "100%" } },
	        react.createElement(keplerGl.KeplerGl, {
	          mapboxApiAccessToken: mapboxToken,
	          id: "map",
	          width:  windowDimension.width,
	          height: windowDimension.height,
	          appName: "",
	          appWebsite: "https://napkingis.no/"
	        })
	      );
	    };
	  }(React, KeplerGl, MAPBOX_TOKEN));

	  const app = (function createReactReduxProvider(react, reactRedux, KeplerElement) {
	    return react.createElement(
	      reactRedux.Provider,
	      { store },
	      react.createElement(KeplerElement, null)
	    )
	  }(React, ReactRedux, KeplerElement));
	  /** END COMPONENTS **/

	  /** Render **/
	  (function render(react, reactDOM, app) {
	    reactDOM.render(app, document.getElementById("app"));
	  }(React, ReactDOM, app));


	  /**
	   * Customize map.
	   * Interact with map store to customize data and behavior
	   */
	  (function customize(keplerGl, store) {
	    //store.dispatch(keplerGl.toggleSplitMap());
	    // let exportMapData = function() { return KeplerGl.KeplerGlSchema.save(store.getState().keplerGl.map); };

			<?php
				$eid = null;

				if(isset($_GET['eid'])) $eid = $_GET['eid'];
				else $eid = "d1c7b8ff-3a11-4834-86ee-81e21cf59ff5";

				$stmt = $pdo->prepare("SELECT data->'config' AS config, data->'datasets' AS datasets FROM \"Entity\" WHERE entityid = ?");
				$stmt->execute([$eid]);
				$res = $stmt->fetch();

				echo "
					const datasets = ".$res['datasets'].";
					const config = ".$res['config'].";

					const loadedData = keplerGl.KeplerGlSchema.load(
						datasets,
						config
					);

					store.dispatch(keplerGl.addDataToMap({
						datasets: loadedData.datasets,
						config: loadedData.config,
						options: {
							centerMap: false
						}
					}));
				";
			?>

	    /*store.dispatch(keplerGl.addDataToMap({
	      datasets: loadedData.datasets,
	      config: loadedData.config,
	      options: {
	        centerMap: false
	      }
	    }));*/
	  }(KeplerGl, store));
  </script>

	<script src="js/index.js"></script>

	<script src="js/main.php.js"></script>

</body>
</html>
