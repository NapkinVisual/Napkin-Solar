#!/usr/bin/env bash

cd ~/Dokumenter/napkin/Napkin-Solar/setup/db/src

sudo -u www-data psql -d solar -f delete.sql

sudo -u www-data psql -d solar -f drop.sql

sudo -u www-data psql -d solar -f create.sql

# NOTE: run some commands manually (see insert.sql)
#sudo -u www-data psql -d solar -f insert.sql
