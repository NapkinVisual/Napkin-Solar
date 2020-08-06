#!/usr/bin/env bash

cd ~/Dokumenter/napkin/Napkin-Visual/server/db/src

sudo -u www-data psql -f delete.sql

sudo -u www-data psql -f drop.sql

sudo -u www-data psql -f create.sql

# NOTE: run some commands manually (see insert.sql)
#sudo -u www-data psql -f insert.sql
