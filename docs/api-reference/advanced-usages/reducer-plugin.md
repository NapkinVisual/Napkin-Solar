# Reducer Plugin

For advanced users, who want to add additional action handler to the reducer, we provide a reducer plugin function. `Reducer.plugin` will take additional action handlers and return a new reducer function. Plugin is only meant to be called where the store is initialized. The state passed into the additional action handler is the instance state.

`Reducer.plugin` will allow advanced users to extend the reducer behavior. Here is an example of adding an additional action `HIDE_AND_SHOW_SIDE_PANEL` handler that modifies the `uiState`.

```js
import {combineReducers} from 'redux';
import keplerGlReducer from 'kepler.gl/reducers';

const customizedKeplerGlReducer = keplerGlReducer
 .plugin({
   HIDE_AND_SHOW_SIDE_PANEL: (state, action) => ({
     ...state,
     uiState: {
       ...state.uiState,
       readOnly: !state.uiState.readOnly
     }
   })
 });

const reducers = combineReducers({
 keplerGl: customizedKeplerGlReducer,
 app: appReducer
});
```

Note that, reducer plugin **should not be used to override default actions** The following code will not change `SET_FILTER`, because plugins are handled after default actions.

```js
const customizedKeplerGlReducer = keplerGlReducer
 .plugin({
   [ActionTypes.SET_FILTER]: (state, action) => state
 });
```

For full implementation, take a look at the [custom reducer example][custom-reducer-example]

[custom-reducer-example]: https://github.com/keplergl/kepler.gl/tree/master/examples/custom-reducer
