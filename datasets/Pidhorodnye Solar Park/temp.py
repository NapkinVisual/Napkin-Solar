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
import csv



with open('panels.geojson', mode='r') as infile, \
     open('panels.csv', mode='w') as outfile:
    data = json.load(infile)
    out = csv.writer(outfile, delimiter=',', quotechar='"', quoting=csv.QUOTE_MINIMAL)
    out.writerow(['lat', 'lng'])

    for f in data['features']:
        coords = f['geometry']['coordinates'][0]
        #out.writerow([coords[0][1], coords[0][0]]); continue

        x1 = coords[0][1]
        y1 = coords[0][0]
        x2 = 0; y2 = 0

        for p in coords:
            if p[1] < x1: x1 = p[1]
            if p[0] < y1: y1 = p[0]

            if p[1] > x2: x2 = p[1]
            if p[0] > y2: y2 = p[0]

        x = x1 + ((x2 - x1) / 2)
        y = y1 + ((y2 - y1) / 2)
        out.writerow([x, y])
