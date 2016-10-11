#!/usr/bin/env bash

# PHP dotdeb
echo -e "${info}Adding dotdeb repository...${NC}"
sudo rm -f /etc/apt/sources.list.d/dotdeb.list
echo "deb http://packages.dotdeb.org jessie all" | sudo tee /etc/apt/sources.list.d/dotdeb.list
echo "deb-src http://packages.dotdeb.org jessie all" | sudo tee -a /etc/apt/sources.list.d/dotdeb.list
wget --quiet -O - http://www.dotdeb.org/dotdeb.gpg | sudo apt-key add -

# Env
sudo apt-get update
sudo apt-get install -y apache2 \
    php7.0-common \
    php7.0-xml \
    php7.0-intl \
    postgresql-9.4 \
    postgresql-contrib-9.4 \
    php7.0-pgsql \
    curl \
    git

# Composer
curl -s http://getcomposer.org/installer | sudo php
sudo cp composer.phar /usr/local/bin/composer

# Project
sudo curl -LsS https://symfony.com/installer -o /usr/local/bin/symfony
sudo chmod a+x /usr/local/bin/symfony
sudo symfony new getpayever.com 2.8
sudo touch /etc/apache2/sites-available/getpayever.com.conf
echo "<VirtualHost *:80>
	ServerName getpayever.com

	ServerAdmin webmaster@localhost
	DocumentRoot /var/www/getpayever.com/web
	<Directory /var/www/getpayever.com/web>
        AllowOverride None
        Order Allow,Deny
        Allow from All

        <IfModule mod_rewrite.c>
            Options -MultiViews
            RewriteEngine On
            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteRule ^(.*)$ app.php [QSA,L]
        </IfModule>
    </Directory>

    <Directory /var/www/getpayever.com/web/bundles>
        <IfModule mod_rewrite.c>
            RewriteEngine Off
        </IfModule>
    </Directory>

	ErrorLog /var/log/apache2/error.log
	CustomLog /var/log/apache2/access.log combined
</VirtualHost>
" | sudo tee /etc/apache2/sites-available/getpayever.com.conf
sudo rm /etc/apache2/sites-enabled/000-default.conf
sudo ln -s /etc/apache2/sites-available/getpayever.com.conf /etc/apache2/sites-enabled/getpayever.com.conf
sudo a2enmod rewrite
sudo service apache2 restart
sudo symfony new getpayever.com 2.8
