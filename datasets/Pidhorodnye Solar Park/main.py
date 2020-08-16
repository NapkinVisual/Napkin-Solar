#!/usr/bin/python3

# /*©agpl*************************************************************************
# *                                                                              *
# * Napkin Solar – Napkin-Visual based analysis tool for the solar energy market *
# * Copyright (C) 2020  Napkin AS                                                *
# *                                                                              *
# * This program is free software: you can redistribute it and/or modify         *
# * it under the terms of the GNU Affero General Public License as published by  *
# * the Free Software Foundation, either version 3 of the License, or            *
# * (at your option) any later version.                                          *
# *                                                                              *
# * This program is distributed in the hope that it will be useful,              *
# * but WITHOUT ANY WARRANTY; without even the implied warranty of               *
# * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the                 *
# * GNU Affero General Public License for more details.                          *
# *                                                                              *
# * You should have received a copy of the GNU Affero General Public License     *
# * along with this program. If not, see <http://www.gnu.org/licenses/>.         *
# *                                                                              *
# *****************************************************************************©*/

import math
import random
import json


lrange = lambda a, b: range(a, b + 1)

F = lambda t, a: math.floor( a * math.sin( (math.pi / 14) * (t - 6) ) )


def production(t):
    if(t > 6 and t < 20):
        return random.randint( F(t, 148), F(t, 150) )
    else:
        return 0

def efficiency(prod):
    return prod / 150



with open('panels.geojson', 'r') as infile, \
     open('out.geojson', 'w') as outfile:
    data = json.load(infile)
    features = [
        {
          'type': 'Feature',
          'properties': {
            'timestamp': '2020-08-03 04:59:00 +00:00',
            'production': 0,
            'efficiency': 0
          },
          'geometry': {
            'type': 'Polygon',
            'coordinates': [
              [ [35.15161380171775, 48.60864887260426], [35.15161380171775, 48.60864887260427], [35.15161380171776, 48.60864887260427], [35.15161380171776, 48.60864887260426], [35.15161380171775, 48.60864887260426] ]
            ]
          }
        },
        {
          'type': 'Feature',
          'properties': {
            'timestamp': '2020-08-03 04:59:00 +00:00',
            'production': 150,
            'efficiency': 1
          },
          'geometry': {
            'type': 'Polygon',
            'coordinates': [
              [ [35.15161380171775, 48.60864887260426], [35.15161380171775, 48.60864887260427], [35.15161380171776, 48.60864887260427], [35.15161380171776, 48.60864887260426], [35.15161380171775, 48.60864887260426] ]
            ]
          }
        }
    ]

    for f in data['features']:

        for t in lrange(5, 21):
            for _s in lrange(0, 30):
                s = _s * 2
                h = f'0{t}' if len(str(t)) < 2 else f'{t}'
                m = f'0{s}' if len(str(s)) < 2 else f'{s}'

                time = t + s / 60
                prod = production(time)
                eff = efficiency(prod)

                feature = f.copy()
                feature['properties'] = {
                    'timestamp': f'2020-08-03 {h}:{m}:00 +00:00',
                    'production': prod,
                    'efficiency': eff
                }
                features.append(feature)

    data['features'] = features
    json.dump(data, outfile)
