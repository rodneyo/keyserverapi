#!/usr/bin/env bash

ODINCA_AD_IP='192.168.75.51'
LOCAL_CONFIG_FILE_PATH='/var/www/keyserver/config/autoload'
LOCAL_CONFIG_FILE_DIST='local.php.dist'
LOCAL_CONFIG_FILE_ACTIVE='local.php'

## Local replace variable values
AD_SERVER_HOST_NAME='odinca.stonemor.local'
DB_USER='dbuser'
DB_PASSWD='123'
cp $LOCAL_CONFIG_FILE_PATH/$LOCAL_CONFIG_FILE_DIST $LOCAL_CONFIG_FILE_PATH/$LOCAL_CONFIG_FILE_ACTIVE

## use sed for variable replacement
## ldap
sed -i -e 's/--AD-LDAP-PORT--/389/' $LOCAL_CONFIG_FILE_PATH/$LOCAL_CONFIG_FILE_ACTIVE
sed -i -e 's/--AD-LDAP-SSL--/false/' $LOCAL_CONFIG_FILE_PATH/$LOCAL_CONFIG_FILE_ACTIVE
sed -i -e 's/--AD-SERVER-HOST-NAME--/odinca.stonemor.local/' $LOCAL_CONFIG_FILE_PATH/$LOCAL_CONFIG_FILE_ACTIVE

## Database
sed -i -e 's/--API-KEY-DB-USER--/dbuser/' $LOCAL_CONFIG_FILE_PATH/$LOCAL_CONFIG_FILE_ACTIVE
sed -i -e 's/--API-KEY-DB-PASSWORD--/123/' $LOCAL_CONFIG_FILE_PATH/$LOCAL_CONFIG_FILE_ACTIVE
sed -i -e 's/--ROLLUP-DB-USER--/dbuser/' $LOCAL_CONFIG_FILE_PATH/$LOCAL_CONFIG_FILE_ACTIVE
sed -i -e 's/--ROLLUP-DB-PASSWORD--/123/' $LOCAL_CONFIG_FILE_PATH/$LOCAL_CONFIG_FILE_ACTIVE

## OdinCA system user. (allows keyserver api to bind to AD)
sed -i -e 's/--AD-SYSTEM-USER--/keyjobs/' $LOCAL_CONFIG_FILE_PATH/$LOCAL_CONFIG_FILE_ACTIVE
sed -i -e 's/--AD-SYSTEM-PASSWORD--/keyJobsDev2016/' $LOCAL_CONFIG_FILE_PATH/$LOCAL_CONFIG_FILE_ACTIVE
sed -i -e 's/--KEYSERVER-API-LOG-LOCATION--/\/tmp\/keyserver.log/' $LOCAL_CONFIG_FILE_PATH/$LOCAL_CONFIG_FILE_ACTIVE

## Odin CA AD Dev IP
ODINCA_IP_EXISTS=$(cat /etc/hosts |grep $ODINCA_AD_IP)
if [[ ! $ODINCA_IP_EXISTS ]]; then
    echo "${ODINCA_AD_IP}    odinca.stonemor.local" >> /etc/hosts
    echo "Adding dev keyserver ip: ${ODINCA_AD_IP}"
fi
cd /var/www/keyserver
composer install