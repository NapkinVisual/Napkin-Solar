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


  //let exportMapData;


  /* MapBox token */
  const MAPBOX_TOKEN = 'pk.eyJ1IjoiYW5kcmVhc2F0YWthbiIsImEiOiJjazlndzM1cmUwMnl5M21tZjQ3dXpzeHJnIn0.oE5zp040ZzJj5QgCDznweg';

  /** STORE **/
  const reducers = (function createReducers(redux, keplerGl) {
    return redux.combineReducers({
      keplerGl: keplerGl.keplerGlReducer.initialState({
        mapState: {
          latitude: 58.655346,
          longitude: 5.951772,
          zoom: 8
        },
        mapStyle: {
          styleType: 'light',
          threeDBuildingColor: [ 192, 192, 192 ]
        },
        uiState: {
          currentModal: null,
          readOnly: true //$("#__isViewer__").length > 0
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
        window.addEventListener('resize', handleResize);
        return function() { window.removeEventListener('resize', handleResize); };
      }, []);

      return react.createElement(
        'div',
        { style: { /*position: 'absolute', left: 0,*/ width: '100%', height: '100%' } },
        react.createElement(keplerGl.KeplerGl, {
          mapboxApiAccessToken: mapboxToken,
          id: 'map',
          width:  windowDimension.width,
          height: windowDimension.height,
          appName: '',
          appWebsite: 'https://napkingis.no'
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
    reactDOM.render(app, document.getElementById('app'));
  }(React, ReactDOM, app));


  /**
   * Customize map.
   * Interact with map store to customize data and behavior
   */
  (function customize(keplerGl, store) {
    //store.dispatch(keplerGl.toggleSplitMap());
    //window.exportMapData = function() { return KeplerGl.KeplerGlSchema.save(store.getState().keplerGl.map); };

    const datasets = [{"version":"v1","data":{"id":"byo07zsv","label":"parks.geojson","color":[143,47,191],"allData":[[{"type":"Feature","properties":{"name":"Egersund","index":0},"geometry":{"type":"Polygon","coordinates":[[[6.081962585449219,58.409805677756275],[6.08642578125,58.40998552353421],[6.093635559082031,58.4085467316074],[6.095695495605469,58.41196376650656],[6.09466552734375,58.414121723074544],[6.0994720458984375,58.4198756277361],[6.1083984375,58.420954380227535],[6.1145782470703125,58.42347134088515],[6.116981506347656,58.426707168849454],[6.116981506347656,58.42868447289883],[6.114234924316406,58.43138061765923],[6.113548278808593,58.433537384813015],[6.115264892578125,58.437131703111895],[6.115951538085937,58.43982720098455],[6.1104583740234375,58.44288184901932],[6.1049652099609375,58.44449890828558],[6.0884857177734375,58.44162408488062],[6.085395812988281,58.437131703111895],[6.086769104003905,58.43389683315898],[6.0871124267578125,58.427066686935724],[6.077499389648437,58.424729753759124],[6.0699462890625,58.420954380227535],[6.065826416015625,58.417718023629675],[6.072349548339844,58.41322259056804],[6.075096130371094,58.41016536839418],[6.081962585449219,58.409805677756275]]]}},"Egersund"],[{"type":"Feature","properties":{"name":"Svåheia","index":1},"geometry":{"type":"Polygon","coordinates":[[[6.090373992919922,58.377238469223826],[6.0919189453125,58.376968449535326],[6.0939788818359375,58.376878442513274],[6.099128723144531,58.377238469223826],[6.102046966552734,58.37939855233451],[6.103076934814453,58.380568542130064],[6.103420257568359,58.38272842134475],[6.100673675537109,58.38434824396612],[6.096038818359375,58.38578802384637],[6.092777252197266,58.38740770595743],[6.090545654296875,58.388757384228185],[6.089515686035156,58.38911728970975],[6.085052490234375,58.389027313683734],[6.082134246826172,58.38956716639626],[6.078701019287109,58.39037692996884],[6.074409484863281,58.38857743010995],[6.071834564208984,58.385068141253186],[6.071834564208984,58.38299839694763],[6.0747528076171875,58.381558503186966],[6.081104278564453,58.379758553327704],[6.090373992919922,58.377238469223826]]]}},"Svåheia"],[{"type":"Feature","properties":{"name":"Tellenes","index":2},"geometry":{"type":"Polygon","coordinates":[[[6.434211730957031,58.336892634740025],[6.444854736328124,58.33959580512613],[6.450004577636719,58.34572222573767],[6.452751159667969,58.35166744154822],[6.453094482421875,58.3572514296907],[6.451377868652344,58.36121372430965],[6.446571350097656,58.36355487120244],[6.436958312988281,58.36301462031584],[6.426658630371094,58.361754002758275],[6.408119201660156,58.359592839350746],[6.403312683105469,58.35563036280964],[6.399536132812499,58.350406418788275],[6.406059265136719,58.34590239849609],[6.4139556884765625,58.34211857757518],[6.41876220703125,58.34013641438728],[6.434211730957031,58.336892634740025]]]}},"Tellenes"],[{"type":"Feature","properties":{"name":"Skinansfjellet og Gravdal","index":3},"geometry":{"type":"Polygon","coordinates":[[[5.860862731933594,58.607260653291426],[5.8536529541015625,58.6086913226201],[5.841636657714843,58.6088701521699],[5.8399200439453125,58.59795987558221],[5.833396911621094,58.59116158895476],[5.840263366699219,58.58328823795818],[5.8522796630859375,58.57774004046545],[5.8715057373046875,58.574518102825934],[5.889701843261719,58.5770240799504],[5.898284912109375,58.580782709287774],[5.8948516845703125,58.58561463968766],[5.881462097167969,58.58704619465218],[5.870475769042969,58.591340508161444],[5.866012573242187,58.59724432867952],[5.860862731933594,58.607260653291426]]]}},"Skinansfjellet og Gravdal"],[{"type":"Feature","properties":{"name":"Bjerkreim","index":4},"geometry":{"type":"Polygon","coordinates":[[[5.895881652832031,58.58579358726105],[5.89862823486328,58.580961681569946],[5.892448425292969,58.57720307145203],[5.894508361816406,58.575592114988495],[5.9003448486328125,58.57380207641193],[5.9099578857421875,58.5718329282609],[5.9250640869140625,58.5718329282609],[5.937080383300781,58.57433909759537],[5.944976806640625,58.57881395378111],[5.949440002441406,58.58364615598293],[5.949783325195312,58.58704619465218],[5.943260192871094,58.58990912888757],[5.94635009765625,58.59205617583809],[5.950469970703125,58.596170980878064],[5.950126647949219,58.59849652615277],[5.9429168701171875,58.60046417446551],[5.936393737792969,58.60261057364717],[5.9319305419921875,58.60475684109427],[5.931587219238281,58.60904898080497],[5.92437744140625,58.6110160354201],[5.917167663574218,58.607260653291426],[5.910301208496094,58.603326010766445],[5.911331176757812,58.60010642846067],[5.914764404296875,58.59849652615277],[5.91888427734375,58.595992086375595],[5.916481018066406,58.592235090469764],[5.910301208496094,58.590803747796386],[5.9058380126953125,58.59062482584466],[5.902061462402343,58.587583012665],[5.897254943847656,58.58740407424245],[5.895881652832031,58.58579358726105]]]}},"Bjerkreim"],[{"type":"Feature","properties":{"name":"Høg-Jæren","index":5},"geometry":{"type":"Polygon","coordinates":[[[5.770740509033203,58.638721857563596],[5.781383514404297,58.64050857560296],[5.779838562011719,58.64247385985622],[5.78155517578125,58.64595750100228],[5.796318054199218,58.64506429282017],[5.80078125,58.64720795406469],[5.804042816162109,58.64935148368018],[5.799236297607421,58.65167349222983],[5.793914794921874,58.6541739440728],[5.790309906005859,58.65604916539446],[5.775718688964844,58.65328094612233],[5.763530731201172,58.65042319912863],[5.773830413818359,58.646850686331646],[5.767993927001953,58.643992412835466],[5.76953887939453,58.64015123930878],[5.770740509033203,58.638721857563596]]]}},"Høg-Jæren"],[{"type":"Feature","properties":{"name":"Stigafjellet","index":6},"geometry":{"type":"Polygon","coordinates":[[[5.967464447021484,58.67274312157914],[5.9593963623046875,58.67167209152742],[5.957508087158203,58.66890511164916],[5.953388214111328,58.66622718017006],[5.947895050048827,58.66604864409465],[5.941886901855469,58.6654237606348],[5.934162139892578,58.66310266692172],[5.9317588806152335,58.659174310349115],[5.934333801269531,58.65622775264603],[5.938968658447266,58.65399534631066],[5.946693420410156,58.6544418390023],[5.949440002441406,58.65703138396822],[5.95458984375,58.66042428996375],[5.961799621582031,58.65926359609236],[5.969524383544922,58.66006715750041],[5.975704193115234,58.661227824640946],[5.9820556640625,58.66631644786505],[5.980682373046875,58.668280279359934],[5.975360870361328,58.67167209152742],[5.971755981445312,58.67327862427042],[5.967464447021484,58.67274312157914]]]}},"Stigafjellet"],[{"type":"Feature","properties":{"name":"Tindafjellet","index":7},"geometry":{"type":"Polygon","coordinates":[[[5.955877304077148,58.731109082248246],[5.952444076538086,58.731910988428616],[5.949611663818359,58.73306926472719],[5.94660758972168,58.73315836130682],[5.942831039428711,58.73306926472719],[5.941715240478516,58.73195553823008],[5.941371917724609,58.73030715758557],[5.938882827758789,58.730262605673516],[5.938024520874023,58.72834681948926],[5.937681198120117,58.72718838597447],[5.9366512298583975,58.72611902850116],[5.94008445739746,58.724693167420575],[5.943517684936523,58.7242475738523],[5.949525833129883,58.7251833137563],[5.953130722045898,58.72540610526327],[5.955448150634766,58.72540610526327],[5.958280563354492,58.72656459810846],[5.9593963623046875,58.72750027571442],[5.95956802368164,58.729505214440366],[5.958538055419922,58.73012894959501],[5.955877304077148,58.731109082248246]]]}},"Tindafjellet"],[{"type":"Feature","properties":{"name":"Skurvenuten","index":8},"geometry":{"type":"Polygon","coordinates":[[[5.916910171508789,58.75257596970956],[5.915622711181641,58.75186358854085],[5.913047790527344,58.751997161121906],[5.909614562988281,58.752219780949915],[5.909528732299805,58.751062142277135],[5.90987205505371,58.74928108443245],[5.911245346069336,58.7471882248766],[5.9127044677734375,58.74580775920242],[5.915365219116211,58.74567416284163],[5.914163589477539,58.74754446518649],[5.913820266723633,58.74945919432266],[5.914850234985352,58.75034973008792],[5.918970108032227,58.7504387824099],[5.919055938720703,58.75146286771843],[5.916910171508789,58.75257596970956]]]}},"Skurvenuten"],[{"type":"Feature","properties":{"name":"Åsen II","index":9},"geometry":{"type":"Polygon","coordinates":[[[5.755205154418945,58.74237862346352],[5.753231048583984,58.7427794489884],[5.75160026550293,58.74153242107364],[5.750398635864258,58.74010719156193],[5.75108528137207,58.73930547429605],[5.748596191406249,58.73841465566043],[5.745935440063477,58.737301100282835],[5.745763778686523,58.73538570164391],[5.747995376586914,58.73333655378156],[5.75160026550293,58.73200008797448],[5.755462646484375,58.73213373686541],[5.755119323730469,58.73409386161734],[5.755119323730469,58.736365686216494],[5.753917694091796,58.73845919713406],[5.7547760009765625,58.7405525820581],[5.755205154418945,58.74237862346352]]]}},"Åsen II"],[{"type":"Feature","properties":{"name":"Tonstad","index":10},"geometry":{"type":"Polygon","coordinates":[[[6.7627716064453125,58.5213133043142],[6.785430908203125,58.50660953112294],[6.79779052734375,58.50517468678928],[6.81427001953125,58.50768562588452],[6.823883056640625,58.5037397838049],[6.83624267578125,58.505892116287356],[6.8424224853515625,58.51414150169539],[6.84173583984375,58.52454013724891],[6.829376220703125,58.53672771646356],[6.8231964111328125,58.54031149255002],[6.832122802734375,58.5507023716455],[6.829376220703125,58.55929939036268],[6.8177032470703125,58.56323898562774],[6.7971038818359375,58.55822487838689],[6.793670654296875,58.55106062287748],[6.78131103515625,58.5449698537912],[6.757965087890625,58.54138655393905],[6.750411987304687,58.5449698537912],[6.74560546875,58.55285182409774],[6.741485595703125,58.56288085891348],[6.749725341796875,58.569326579522105],[6.75933837890625,58.57433909759537],[6.767578125,58.58794088676484],[6.75933837890625,58.59330855904495],[6.7421722412109375,58.59438199468101],[6.7256927490234375,58.58722513490484],[6.7325592041015625,58.58221446192042],[6.7298126220703125,58.577919028306106],[6.730499267578125,58.56323898562774],[6.734619140625,58.55535935197943],[6.7401123046875,58.54210324321517],[6.750411987304687,58.53744450098898],[6.7627716064453125,58.532785139620565],[6.7627716064453125,58.5213133043142]]]}},"Tonstad"]],"fields":[{"name":"_geojson","type":"geojson","format":"","analyzerType":"GEOMETRY"},{"name":"name","type":"string","format":"","analyzerType":"STRING"}]}},{"version":"v1","data":{"id":"h875sngvs","label":"parks.csv","color":[0,92,255],"allData":[["Norway","Rogaland","Åsen II",82,58.736629,5.751161,"2020-01-01 00:00:00 +00:00"],["Norway","Rogaland","Åsen II",129,58.736629,5.751161,"2020-02-01 00:00:00 +00:00"],["Norway","Rogaland","Åsen II",106,58.736629,5.751161,"2020-03-01 00:00:00 +00:00"],["Norway","Rogaland","Åsen II",94,58.736629,5.751161,"2020-04-01 00:00:00 +00:00"],["Norway","Rogaland","Åsen II",68,58.736629,5.751161,"2020-05-01 00:00:00 +00:00"],["Norway","Rogaland","Åsen II",121,58.736629,5.751161,"2020-06-01 00:00:00 +00:00"],["Norway","Rogaland","Åsen II",123,58.736629,5.751161,"2020-07-01 00:00:00 +00:00"],["Norway","Rogaland","Åsen II",145,58.736629,5.751161,"2020-08-01 00:00:00 +00:00"],["Norway","Rogaland","Åsen II",64,58.736629,5.751161,"2020-09-01 00:00:00 +00:00"],["Norway","Rogaland","Åsen II",54,58.736629,5.751161,"2020-10-01 00:00:00 +00:00"],["Norway","Rogaland","Åsen II",97,58.736629,5.751161,"2020-11-01 00:00:00 +00:00"],["Norway","Rogaland","Åsen II",81,58.736629,5.751161,"2020-12-01 00:00:00 +00:00"],["Norway","Rogaland","Skurvenuten",77,58.749475,5.912262,"2020-01-01 00:00:00 +00:00"],["Norway","Rogaland","Skurvenuten",133,58.749476,5.912263,"2020-02-01 00:00:00 +00:00"],["Norway","Rogaland","Skurvenuten",60,58.749477,5.912264,"2020-03-01 00:00:00 +00:00"],["Norway","Rogaland","Skurvenuten",90,58.749478,5.912265,"2020-04-01 00:00:00 +00:00"],["Norway","Rogaland","Skurvenuten",126,58.749479,5.912266,"2020-05-01 00:00:00 +00:00"],["Norway","Rogaland","Skurvenuten",93,58.74948,5.912267,"2020-06-01 00:00:00 +00:00"],["Norway","Rogaland","Skurvenuten",67,58.749481,5.912268,"2020-07-01 00:00:00 +00:00"],["Norway","Rogaland","Skurvenuten",132,58.749482,5.912269,"2020-08-01 00:00:00 +00:00"],["Norway","Rogaland","Skurvenuten",86,58.749483,5.91227,"2020-09-01 00:00:00 +00:00"],["Norway","Rogaland","Skurvenuten",68,58.749484,5.912271,"2020-10-01 00:00:00 +00:00"],["Norway","Rogaland","Skurvenuten",95,58.749485,5.912272,"2020-11-01 00:00:00 +00:00"],["Norway","Rogaland","Skurvenuten",113,58.749486,5.912273,"2020-12-01 00:00:00 +00:00"],["Norway","Rogaland","Tindafjellet",67,58.728316,5.948337,"2020-01-01 00:00:00 +00:00"],["Norway","Rogaland","Tindafjellet",98,58.728317,5.948338,"2020-02-01 00:00:00 +00:00"],["Norway","Rogaland","Tindafjellet",143,58.728318,5.948339,"2020-03-01 00:00:00 +00:00"],["Norway","Rogaland","Tindafjellet",97,58.728319,5.94834,"2020-04-01 00:00:00 +00:00"],["Norway","Rogaland","Tindafjellet",77,58.72832,5.948341,"2020-05-01 00:00:00 +00:00"],["Norway","Rogaland","Tindafjellet",78,58.728321,5.948342,"2020-06-01 00:00:00 +00:00"],["Norway","Rogaland","Tindafjellet",96,58.728322,5.948343,"2020-07-01 00:00:00 +00:00"],["Norway","Rogaland","Tindafjellet",101,58.728323,5.948344,"2020-08-01 00:00:00 +00:00"],["Norway","Rogaland","Tindafjellet",141,58.728324,5.948345,"2020-09-01 00:00:00 +00:00"],["Norway","Rogaland","Tindafjellet",120,58.728325,5.948346,"2020-10-01 00:00:00 +00:00"],["Norway","Rogaland","Tindafjellet",59,58.728326,5.948347,"2020-11-01 00:00:00 +00:00"],["Norway","Rogaland","Tindafjellet",69,58.728327,5.948348,"2020-12-01 00:00:00 +00:00"],["Norway","Rogaland","Stigafjellet",82,58.663895,5.956961,"2020-01-01 00:00:00 +00:00"],["Norway","Rogaland","Stigafjellet",129,58.663896,5.956962,"2020-02-01 00:00:00 +00:00"],["Norway","Rogaland","Stigafjellet",106,58.663897,5.956963,"2020-03-01 00:00:00 +00:00"],["Norway","Rogaland","Stigafjellet",94,58.663898,5.956964,"2020-04-01 00:00:00 +00:00"],["Norway","Rogaland","Stigafjellet",68,58.663899,5.956965,"2020-05-01 00:00:00 +00:00"],["Norway","Rogaland","Stigafjellet",121,58.6639,5.956966,"2020-06-01 00:00:00 +00:00"],["Norway","Rogaland","Stigafjellet",123,58.663901,5.956967,"2020-07-01 00:00:00 +00:00"],["Norway","Rogaland","Stigafjellet",145,58.663902,5.956968,"2020-08-01 00:00:00 +00:00"],["Norway","Rogaland","Stigafjellet",64,58.663903,5.956969,"2020-09-01 00:00:00 +00:00"],["Norway","Rogaland","Stigafjellet",54,58.663904,5.95697,"2020-10-01 00:00:00 +00:00"],["Norway","Rogaland","Stigafjellet",97,58.663905,5.956971,"2020-11-01 00:00:00 +00:00"],["Norway","Rogaland","Stigafjellet",81,58.663906,5.956972,"2020-12-01 00:00:00 +00:00"],["Norway","Rogaland","Høg-Jæren",67,58.649598,5.780979,"2020-01-01 00:00:00 +00:00"],["Norway","Rogaland","Høg-Jæren",98,58.649599,5.78098,"2020-02-01 00:00:00 +00:00"],["Norway","Rogaland","Høg-Jæren",143,58.6496,5.780981,"2020-03-01 00:00:00 +00:00"],["Norway","Rogaland","Høg-Jæren",97,58.649601,5.780982,"2020-04-01 00:00:00 +00:00"],["Norway","Rogaland","Høg-Jæren",77,58.649602,5.780983,"2020-05-01 00:00:00 +00:00"],["Norway","Rogaland","Høg-Jæren",78,58.649603,5.780984,"2020-06-01 00:00:00 +00:00"],["Norway","Rogaland","Høg-Jæren",96,58.649604,5.780985,"2020-07-01 00:00:00 +00:00"],["Norway","Rogaland","Høg-Jæren",101,58.649605,5.780986,"2020-08-01 00:00:00 +00:00"],["Norway","Rogaland","Høg-Jæren",141,58.649606,5.780987,"2020-09-01 00:00:00 +00:00"],["Norway","Rogaland","Høg-Jæren",120,58.649607,5.780988,"2020-10-01 00:00:00 +00:00"],["Norway","Rogaland","Høg-Jæren",59,58.649608,5.780989,"2020-11-01 00:00:00 +00:00"],["Norway","Rogaland","Høg-Jæren",69,58.649609,5.78099,"2020-12-01 00:00:00 +00:00"],["Norway","Rogaland","Bjerkreim",77,58.58572,5.926227,"2020-01-01 00:00:00 +00:00"],["Norway","Rogaland","Bjerkreim",133,58.585721,5.926228,"2020-02-01 00:00:00 +00:00"],["Norway","Rogaland","Bjerkreim",60,58.585722,5.926229,"2020-03-01 00:00:00 +00:00"],["Norway","Rogaland","Bjerkreim",90,58.585723,5.92623,"2020-04-01 00:00:00 +00:00"],["Norway","Rogaland","Bjerkreim",126,58.585724,5.926231,"2020-05-01 00:00:00 +00:00"],["Norway","Rogaland","Bjerkreim",93,58.585725,5.926232,"2020-06-01 00:00:00 +00:00"],["Norway","Rogaland","Bjerkreim",67,58.585726,5.926233,"2020-07-01 00:00:00 +00:00"],["Norway","Rogaland","Bjerkreim",132,58.585727,5.926234,"2020-08-01 00:00:00 +00:00"],["Norway","Rogaland","Bjerkreim",86,58.585728,5.926235,"2020-09-01 00:00:00 +00:00"],["Norway","Rogaland","Bjerkreim",68,58.585729,5.926236,"2020-10-01 00:00:00 +00:00"],["Norway","Rogaland","Bjerkreim",95,58.58573,5.926237,"2020-11-01 00:00:00 +00:00"],["Norway","Rogaland","Bjerkreim",113,58.585731,5.926238,"2020-12-01 00:00:00 +00:00"],["Norway","Rogaland","Skinansfjellet og Gravdal",77,58.588128,5.860019,"2020-01-01 00:00:00 +00:00"],["Norway","Rogaland","Skinansfjellet og Gravdal",150,58.588129,5.86002,"2020-02-01 00:00:00 +00:00"],["Norway","Rogaland","Skinansfjellet og Gravdal",67,58.58813,5.860021,"2020-03-01 00:00:00 +00:00"],["Norway","Rogaland","Skinansfjellet og Gravdal",85,58.588131,5.860022,"2020-04-01 00:00:00 +00:00"],["Norway","Rogaland","Skinansfjellet og Gravdal",130,58.588132,5.860023,"2020-05-01 00:00:00 +00:00"],["Norway","Rogaland","Skinansfjellet og Gravdal",92,58.588133,5.860024,"2020-06-01 00:00:00 +00:00"],["Norway","Rogaland","Skinansfjellet og Gravdal",67,58.588134,5.860025,"2020-07-01 00:00:00 +00:00"],["Norway","Rogaland","Skinansfjellet og Gravdal",132,58.588135,5.860026,"2020-08-01 00:00:00 +00:00"],["Norway","Rogaland","Skinansfjellet og Gravdal",96,58.588136,5.860027,"2020-09-01 00:00:00 +00:00"],["Norway","Rogaland","Skinansfjellet og Gravdal",60,58.588137,5.860028,"2020-10-01 00:00:00 +00:00"],["Norway","Rogaland","Skinansfjellet og Gravdal",90,58.588138,5.860029,"2020-11-01 00:00:00 +00:00"],["Norway","Rogaland","Skinansfjellet og Gravdal",113,58.588139,5.86003,"2020-12-01 00:00:00 +00:00"],["Norway","Rogaland","Egersund",82,58.424807,6.093962,"2020-01-01 00:00:00 +00:00"],["Norway","Rogaland","Egersund",129,58.424808,6.093963,"2020-02-01 00:00:00 +00:00"],["Norway","Rogaland","Egersund",106,58.424809,6.093964,"2020-03-01 00:00:00 +00:00"],["Norway","Rogaland","Egersund",94,58.42481,6.093965,"2020-04-01 00:00:00 +00:00"],["Norway","Rogaland","Egersund",68,58.424811,6.093966,"2020-05-01 00:00:00 +00:00"],["Norway","Rogaland","Egersund",121,58.424812,6.093967,"2020-06-01 00:00:00 +00:00"],["Norway","Rogaland","Egersund",123,58.424813,6.093968,"2020-07-01 00:00:00 +00:00"],["Norway","Rogaland","Egersund",145,58.424814,6.093969,"2020-08-01 00:00:00 +00:00"],["Norway","Rogaland","Egersund",64,58.424815,6.09397,"2020-09-01 00:00:00 +00:00"],["Norway","Rogaland","Egersund",54,58.424816,6.093971,"2020-10-01 00:00:00 +00:00"],["Norway","Rogaland","Egersund",97,58.424817,6.093972,"2020-11-01 00:00:00 +00:00"],["Norway","Rogaland","Egersund",81,58.424818,6.093973,"2020-12-01 00:00:00 +00:00"],["Norway","Rogaland","Svåheia",54,58.382645,6.08695,"2020-01-01 00:00:00 +00:00"],["Norway","Rogaland","Svåheia",59,58.382646,6.086951,"2020-02-01 00:00:00 +00:00"],["Norway","Rogaland","Svåheia",50,58.382647,6.086952,"2020-03-01 00:00:00 +00:00"],["Norway","Rogaland","Svåheia",92,58.382648,6.086953,"2020-04-01 00:00:00 +00:00"],["Norway","Rogaland","Svåheia",94,58.382649,6.086954,"2020-05-01 00:00:00 +00:00"],["Norway","Rogaland","Svåheia",120,58.38265,6.086955,"2020-06-01 00:00:00 +00:00"],["Norway","Rogaland","Svåheia",123,58.382651,6.086956,"2020-07-01 00:00:00 +00:00"],["Norway","Rogaland","Svåheia",145,58.382652,6.086957,"2020-08-01 00:00:00 +00:00"],["Norway","Rogaland","Svåheia",64,58.382653,6.086958,"2020-09-01 00:00:00 +00:00"],["Norway","Rogaland","Svåheia",65,58.382654,6.086959,"2020-10-01 00:00:00 +00:00"],["Norway","Rogaland","Svåheia",97,58.382655,6.08696,"2020-11-01 00:00:00 +00:00"],["Norway","Rogaland","Svåheia",90,58.382656,6.086961,"2020-12-01 00:00:00 +00:00"],["Norway","Rogaland","Tellenes",77,58.351331,6.429848,"2020-01-01 00:00:00 +00:00"],["Norway","Rogaland","Tellenes",133,58.351332,6.429849,"2020-02-01 00:00:00 +00:00"],["Norway","Rogaland","Tellenes",60,58.351333,6.42985,"2020-03-01 00:00:00 +00:00"],["Norway","Rogaland","Tellenes",90,58.351334,6.429851,"2020-04-01 00:00:00 +00:00"],["Norway","Rogaland","Tellenes",126,58.351335,6.429852,"2020-05-01 00:00:00 +00:00"],["Norway","Rogaland","Tellenes",93,58.351336,6.429853,"2020-06-01 00:00:00 +00:00"],["Norway","Rogaland","Tellenes",67,58.351337,6.429854,"2020-07-01 00:00:00 +00:00"],["Norway","Rogaland","Tellenes",132,58.351338,6.429855,"2020-08-01 00:00:00 +00:00"],["Norway","Rogaland","Tellenes",86,58.351339,6.429856,"2020-09-01 00:00:00 +00:00"],["Norway","Rogaland","Tellenes",68,58.35134,6.429857,"2020-10-01 00:00:00 +00:00"],["Norway","Rogaland","Tellenes",95,58.351341,6.429858,"2020-11-01 00:00:00 +00:00"],["Norway","Rogaland","Tellenes",113,58.351342,6.429859,"2020-12-01 00:00:00 +00:00"],["Norway","Rogaland","Tonstad",null,58.530166,6.798735,null],["Norway","Rogaland","Tonstad",140,58.530167,6.798736,"2020-01-01 00:00:00 +00:00"],["Norway","Rogaland","Tonstad",144,58.530168,6.798737,"2020-02-01 00:00:00 +00:00"],["Norway","Rogaland","Tonstad",120,58.530169,6.798738,"2020-03-01 00:00:00 +00:00"],["Norway","Rogaland","Tonstad",132,58.53017,6.798739,"2020-04-01 00:00:00 +00:00"],["Norway","Rogaland","Tonstad",137,58.530171,6.79874,"2020-05-01 00:00:00 +00:00"],["Norway","Rogaland","Tonstad",103,58.530172,6.798741,"2020-06-01 00:00:00 +00:00"],["Norway","Rogaland","Tonstad",110,58.530173,6.798742,"2020-07-01 00:00:00 +00:00"],["Norway","Rogaland","Tonstad",111,58.530174,6.798743,"2020-08-01 00:00:00 +00:00"],["Norway","Rogaland","Tonstad",146,58.530175,6.798744,"2020-09-01 00:00:00 +00:00"],["Norway","Rogaland","Tonstad",150,58.530176,6.798745,"2020-10-01 00:00:00 +00:00"],["Norway","Rogaland","Tonstad",139,58.530177,6.798746,"2020-11-01 00:00:00 +00:00"],["Norway","Rogaland","Tonstad",113,58.530178,6.798747,"2020-12-01 00:00:00 +00:00"]],"fields":[{"name":"Country","type":"string","format":"","analyzerType":"STRING"},{"name":"State/Province","type":"string","format":"","analyzerType":"STRING"},{"name":"Name","type":"string","format":"","analyzerType":"STRING"},{"name":"Production","type":"integer","format":"","analyzerType":"INT"},{"name":"Lat","type":"real","format":"","analyzerType":"FLOAT"},{"name":"Lon","type":"real","format":"","analyzerType":"FLOAT"},{"name":"Timestamp","type":"timestamp","format":"YYYY-M-D H:m:s","analyzerType":"DATETIME"}]}}];
    const config = {"version":"v1","config":{"visState":{"filters":[{"dataId":["h875sngvs"],"id":"9ayx3k138r","name":["Timestamp"],"type":"timeRange","value":[1603759949000,1606780800000],"enlarged":true,"plotType":"histogram","yAxis":null}],"layers":[{"id":"0dw9xki","type":"hexagon","config":{"dataId":"h875sngvs","label":"production","color":[130,154,227],"columns":{"lat":"Lat","lng":"Lon"},"isVisible":true,"visConfig":{"opacity":0.8,"worldUnitSize":0.3,"resolution":8,"colorRange":{"name":"Global Warming","type":"sequential","category":"Uber","colors":["#5A1846","#900C3F","#C70039","#E3611C","#F1920E","#FFC300"]},"coverage":1,"sizeRange":[0,500],"percentile":[0,100],"elevationPercentile":[0,100],"elevationScale":30,"colorAggregation":"count","sizeAggregation":"average","enable3d":true},"hidden":false,"textLabel":[{"field":null,"color":[255,255,255],"size":18,"offset":[0,0],"anchor":"start","alignment":"center"}]},"visualChannels":{"colorField":null,"colorScale":"quantile","sizeField":{"name":"Production","type":"integer"},"sizeScale":"linear"}},{"id":"6d9grpy","type":"geojson","config":{"dataId":"byo07zsv","label":"parks","color":[255,203,153],"columns":{"geojson":"_geojson"},"isVisible":true,"visConfig":{"opacity":0.8,"strokeOpacity":0.8,"thickness":0.5,"strokeColor":[248,149,112],"colorRange":{"name":"Global Warming","type":"sequential","category":"Uber","colors":["#5A1846","#900C3F","#C70039","#E3611C","#F1920E","#FFC300"]},"strokeColorRange":{"name":"Global Warming","type":"sequential","category":"Uber","colors":["#5A1846","#900C3F","#C70039","#E3611C","#F1920E","#FFC300"]},"radius":10,"sizeRange":[0,10],"radiusRange":[0,50],"heightRange":[0,500],"elevationScale":1,"stroked":true,"filled":true,"enable3d":true,"wireframe":false},"hidden":false,"textLabel":[{"field":null,"color":[255,255,255],"size":18,"offset":[0,0],"anchor":"start","alignment":"center"}]},"visualChannels":{"colorField":null,"colorScale":"quantile","sizeField":null,"sizeScale":"linear","strokeColorField":null,"strokeColorScale":"quantile","heightField":null,"heightScale":"linear","radiusField":null,"radiusScale":"linear"}}],"interactionConfig":{"tooltip":{"fieldsToShow":{"byo07zsv":["name"],"h875sngvs":["Country","State/Province","Name","Production","Lat"]},"enabled":true},"brush":{"size":0.5,"enabled":false},"geocoder":{"enabled":false},"coordinate":{"enabled":false}},"layerBlending":"normal","splitMaps":[],"animationConfig":{"currentTime":null,"speed":1}},"mapState":{"bearing":11.662565905096661,"dragRotate":true,"latitude":58.34441178684917,"longitude":6.180346509498847,"pitch":59.43722626668034,"zoom":8.759672619963176,"isSplit":false},"mapStyle":{"styleType":"light","topLayerGroups":{},"visibleLayerGroups":{"label":true,"road":true,"border":false,"building":true,"water":true,"land":true,"3d building":false},"threeDBuildingColor":[218.82023004728686,223.47597962276103,223.47597962276103],"mapStyles":{}}}};

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
  }(KeplerGl, store));
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
