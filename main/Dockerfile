#!/bin/bash
FROM domdest/lamp_base

LABEL MAINTAINER="ddest@stonemor.com"

ARG PROJECT_NAME
ENV PROJECT_NAME=$PROJECT_NAME
LABEL PROJECT=$PROJECT_NAME

ARG MYSQL_ROOT_PASSWORD
ENV MYSQL_ROOT_PASSWORD=$MYSQL_ROOT_PASSWORD

RUN /bin/bash -c "debconf-set-selections <<< 'mysql-server-5.6 mysql-server/root_password password $MYSQL_ROOT_PASSWORD'"

RUN /bin/bash -c "debconf-set-selections <<< 'mysql-server-5.6 mysql-server/root_password_again password $MYSQL_ROOT_PASSWORD'"

RUN apt update && \
apt install -y \
php7.2-ldap \
sendmail

ENV APPLICATION_ENV=$APPLICATION_ENV

COPY ./configs /root/configs
COPY ./configs/addl-configs.conf /etc/apache2/conf-available/

ENV PROJECT_LOG=/var/log/keyserver
RUN mkdir $PROJECT_LOG && \
touch $PROJECT_LOG/app.log $PROJECT_LOG/keyserver_http_client.log $PROJECT_LOG/stonemor_auth.log && \
chmod 777 $PROJECT_LOG

ARG CURRENT_USER=1000
ENV CURRENT_USER=$CURRENT_USER

CMD ["/root/configs/app_setup.sh"]