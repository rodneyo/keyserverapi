#!/bin/bash

HOSTNAME=`hostname`

echo "MySQL login credentials:
Host: $MYSQL_HOSTNAME
Username: $MYSQL_USER
Password: $MYSQL_PASSWORD
DB: $MYSQL_DATABASE" >> $PROJECT_LOG/app.log

/usr/sbin/a2enconf addl-configs

cp /root/configs/vhost.tpl /etc/apache2/sites-available/000-default.conf

/usr/sbin/a2enmod cache unique_id

chown -R mysql:mysql /var/lib/mysql

cp /root/configs/mysqld.cnf /etc/mysql/mysql.conf.d/mysqld.cnf

service mysql restart

## Local replace variable values
cp $LOCAL_CONFIG_FILE_PATH/$LOCAL_CONFIG_FILE_DIST $LOCAL_CONFIG_FILE_PATH/$LOCAL_CONFIG_FILE_ACTIVE

## use sed for variable replacement
## ldap
sed -i -e "s/--AD-LDAP-PORT--/389/" $LOCAL_CONFIG_FILE_PATH/$LOCAL_CONFIG_FILE_ACTIVE
sed -i -e "s/--AD-LDAP-SSL--/false/" $LOCAL_CONFIG_FILE_PATH/$LOCAL_CONFIG_FILE_ACTIVE
sed -i -e "s/--AD-SERVER-HOST-NAME--/$AD_SERVER_HOST_NAME/" $LOCAL_CONFIG_FILE_PATH/$LOCAL_CONFIG_FILE_ACTIVE

## Database
sed -i -e "s/--API-KEY-DB-USER--/$MYSQL_USER/" $LOCAL_CONFIG_FILE_PATH/$LOCAL_CONFIG_FILE_ACTIVE
sed -i -e "s/--API-KEY-DB-PASSWORD--/$MYSQL_PASSWORD/" $LOCAL_CONFIG_FILE_PATH/$LOCAL_CONFIG_FILE_ACTIVE
sed -i -e "s/--ROLLUP-DB-USER--/$MYSQL_USER/" $LOCAL_CONFIG_FILE_PATH/$LOCAL_CONFIG_FILE_ACTIVE
sed -i -e "s/--ROLLUP-DB-PASSWORD--/$MYSQL_PASSWORD/" $LOCAL_CONFIG_FILE_PATH/$LOCAL_CONFIG_FILE_ACTIVE

## OdinCA system user. (allows keyserver api to bind to AD)
sed -i -e "s/--AD-SYSTEM-USER--/$AD_SYSTEM_USER/" $LOCAL_CONFIG_FILE_PATH/$LOCAL_CONFIG_FILE_ACTIVE
sed -i -e "s/--AD-SYSTEM-PASSWORD--/$AD_SYSTEM_PASSWORD/" $LOCAL_CONFIG_FILE_PATH/$LOCAL_CONFIG_FILE_ACTIVE
sed -i -e "s@--KEYSERVER-API-LOG-LOCATION--@/var/log/keyserver/keyserver.log@" $LOCAL_CONFIG_FILE_PATH/$LOCAL_CONFIG_FILE_ACTIVE

# Update MySQL user name and password in init script.
cp /root/configs/sql/init.sql.dist /root/configs/sql/init.sql
sed -i -e "s@--DB_USER_NAME--@$MYSQL_USER@" /root/configs/sql/init.sql
sed -i -e "s@--DB_USER_PASSWORD--@$MYSQL_PASSWORD@" /root/configs/sql/init.sql

sed -i -e "s@--TEST USER--@$TEST_ROLES_USER@" /root/configs/sql/init.sql
sed -i -e "s@--TEST EMAIL--@$TEST_ROLES_EMAIL@" /root/configs/sql/init.sql

mysql -u root -p$MYSQL_ROOT_PASSWORD < /root/configs/sql/init.sql
mysql -u $MYSQL_USER -p$MYSQL_PASSWORD -D apikey < /root/configs/sql/apikey.sql
mysql -u $MYSQL_USER -p$MYSQL_PASSWORD -D rollup < /root/configs/sql/rollup.sql
mysql -u $MYSQL_USER -p$MYSQL_PASSWORD -D rollup < /root/configs/sql/definers.sql

cat /root/configs/add_hosts >> /etc/hosts

curl https://getcomposer.org/download/1.5.2/composer.phar > /usr/local/bin/composer
chmod +x /usr/local/bin/composer

a2enmod rewrite headers
service apache2 start

cat /root/configs/bash_profile >> ~/.bash_profile
source ~/.bash_profile

cat $PROJECT_LOG/app.log

echo "This container is ready for use!"

tail -f /var/log/keyserver/error.log