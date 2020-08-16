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
* along with this program.  If not, see <http://www.gnu.org/licenses/>.        *
*                                                                              *
*****************************************************************************©*/

"use strict";

window.addEventListener("load", function() {
  feather.replace();
  init();

  let exportMapData;
  let params = getParams(window.location.href);
  let entityid = params.eid;


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
          readOnly: true
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
    exportMapData = function() { return KeplerGl.KeplerGlSchema.save(store.getState().keplerGl.map); };

    if(!entityid) return;

    $("#loadingModal").modal("show");

    $.ajax({
      type: "GET",
      url: "php/entity",
      data: {
        "op": "get_data",
        "eid": entityid
      },
      dataType: "json",
      success: function(result, status, xhr) {
        let data = result;
        data = JSON.parse(data);

        const loadedData = keplerGl.KeplerGlSchema.load(
          data.datasets,
          data.config
        );

        store.dispatch(keplerGl.addDataToMap({
          datasets: loadedData.datasets,
          config: loadedData.config,
          options: {
            centerMap: false
          }
        }));

        setTimeout(function() {
          $("#loadingModal").modal("hide");
        }, 600);
      },
      error: function(xhr, status, error) {
        console.log(xhr.status);
        console.log(error);
      }
    });

    /*store.dispatch(keplerGl.addDataToMap({
      datasets: loadedData.datasets,
      config: loadedData.config,
      options: {
        centerMap: false
      }
    }));*/
  }(KeplerGl, store));


  /*window.__saveMap = function() {
    let data = exportMapData();

    for(let d of data.datasets) {
      d.info = {
        id: d.data.id,
        label: d.data.label
      };

      d.data = {
        fields: d.data.fields,
        allData: d.data.allData
      };
    }

    $.ajax({
      type: "POST",
      url: "php/entity",
      data: {
        "op": "save",
        "eid": entityid,
        "data": JSON.stringify(data)
      },
      //dataType: "json",
      success: function(result, status, xhr) {
        console.log(status);
      },
      error: function(xhr, status, error) {
        console.log(xhr.status);
        console.log(error);
      }
    });
  };*/
});




function calcWidth(width) {
  let W = window.innerWidth;

  if(W > 1199)
    return (1 - 1/6)*width;
  else
  if(W > 991)
    return (1 - 1/6)*width;
  else
  if(W > 767)
    return (1 - 3/12)*width;
  else
  if(W > 575)
    return width;

  return width;
}

function calcHeight(height) {
  let W = window.innerWidth;

  if(W > 1199)
    return height - 54;
  else
  if(W > 991)
    return height - 54;
  else
  if(W > 767)
    return height - 54;
  else
  if(W > 575)
    return height - 129;

  return height - 129;
}



function getParams(url) {
	let params = {},
      parser = document.createElement('a');
	parser.href = url;

	let query = parser.search.substring(1);
	let vars = query.split('&');

	for(let i = 0; i < vars.length; i++) {
		let pair = vars[i].split('=');
		params[pair[0]] = decodeURIComponent(pair[1]);
	}

	return params;
}
