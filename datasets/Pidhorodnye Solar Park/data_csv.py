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
import csv


lrange = lambda a, b: range(a, b + 1)

F = lambda t, a: math.floor( a * math.sin( (math.pi / 14) * (t - 6) ) )


def production(t):
    if(t > 6 and t < 20):
        return random.randint( F(t, 140), F(t, 150) )
    else:
        return 0

def efficiency(prod):
    return prod / 150



with open('panels.csv', mode='r') as infile, \
     open('raw_data/out.csv', mode='w') as outfile:
    csv_reader = csv.reader(infile, delimiter=',')
    csv_writer = csv.writer(outfile, delimiter=',', quotechar='"', quoting=csv.QUOTE_MINIMAL)

    csv_writer.writerow(['lat', 'lng', 'production', 'efficiency', 'timestamp'])

    skip = True
    for r in csv_reader:
        if skip: skip = False; continue

        for t in lrange(4, 22):
            for s in lrange(0, 60):
                h = f'0{t}' if len(str(t)) < 2 else f'{t}'
                m = f'0{s}' if len(str(s)) < 2 else f'{s}'

                time = t + s / 60
                prod = production(time)
                eff = efficiency(prod)
                timestamp = f'2020-08-03 {h}:{m}:00 +00:00'

                csv_writer.writerow([r[0], r[1], prod, eff, timestamp])

    csv_writer.writerow([0, 0, 0, 0, '2020-08-03 05:00:00 +00:00'])
    csv_writer.writerow([0, 0, 0, 0, '2020-08-03 05:00:00 +00:00'])
