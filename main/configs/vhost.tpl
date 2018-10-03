# Created by Zend Server

<VirtualHost *:80>
	ServerName keyserver.stonemor.local
	DocumentRoot "/var/www/html/public"
	<Directory "/var/www/html">
	Options +Indexes +FollowSymLinks
	DirectoryIndex index.php
	AllowOverride All
	Require all granted
	</Directory>

	LogLevel warn
	ErrorLog /var/log/keyserver/error.log
	CustomLog /var/log/keyserver/access.log combined

	SetEnvIf Authorization "(.*)" HTTP_AUTHORIZATION=$1

</VirtualHost>