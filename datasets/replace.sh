#!/usr/bin/env bash

sed -e '/\/\*\[__REPLACE__\]\*\// {' -e 'r DATA.txt' -e 'd' -e '}' -i pidhorodnye_historical.html
