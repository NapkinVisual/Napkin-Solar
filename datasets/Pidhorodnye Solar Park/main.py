#!/usr/bin/python3

import json

with open('panels.geojson', 'r') as file:
    data = json.load(file)

    for f in data['features']:
        print(f['properties'])
