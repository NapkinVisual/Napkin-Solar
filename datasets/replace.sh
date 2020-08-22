#!/usr/bin/env bash

sed -e '/\/\*\[__REPLACE__\]\*\// {' -e 'r DATA.txt' -e 'd' -e '}' -i raw_pidhorodnye_historical.html
