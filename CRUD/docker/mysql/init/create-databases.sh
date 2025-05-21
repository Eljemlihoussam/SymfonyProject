#!/bin/bash

set -e

# Création des bases de données
mysql -u root -p"$MYSQL_ROOT_PASSWORD" <<-EOSQL
    CREATE DATABASE IF NOT EXISTS crm;
    CREATE DATABASE IF NOT EXISTS crm_test;
    GRANT ALL PRIVILEGES ON crm.* TO 'root'@'%';
    GRANT ALL PRIVILEGES ON crm_test.* TO 'root'@'%';
    FLUSH PRIVILEGES;
EOSQL 