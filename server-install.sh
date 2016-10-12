#!/usr/bin/env bash

PG_VERSION='9.4' # Postgresql version

# PHP dotdeb
echo -e "${info}Adding dotdeb repository...${NC}"
sudo rm -f /etc/apt/sources.list.d/dotdeb.list
echo "deb http://packages.dotdeb.org jessie all" | sudo tee /etc/apt/sources.list.d/dotdeb.list
echo "deb-src http://packages.dotdeb.org jessie all" | sudo tee -a /etc/apt/sources.list.d/dotdeb.list
wget --quiet -O - http://www.dotdeb.org/dotdeb.gpg | sudo apt-key add -

# Fix
curl -o- https://raw.githubusercontent.com/creationix/nvm/v0.32.0/install.sh | bash
echo "v6.3.0" > /var/www/.nvmrc
nvm install v6.3.0
nvm use

# Nodejs
curl -sL https://deb.nodesource.com/setup_6.x | sudo -E bash -

# Env
sudo apt-get update
sudo apt-get install -y apache2 \
    php7.0-common \
    php7.0-xml \
    php7.0-intl \
    postgresql-$PG_VERSION \
    postgresql-contrib-$PG_VERSION \
    php7.0-pgsql \
    curl \
    git \
    npm \
    php7.0-xdebug

# Install bower
sudo npm install -g bower

# Install webpack
sudo npm install webpack -g

# Add to apache
sudo ln -s /etc/php/7.0/mods-available/xdebug.ini /etc/php/7.0/apache2/xdebug.ini
sudo chmod 0777 /etc/php/7.0/mods-available/xdebug.ini
echo "

xdebug.remote_enable=1
xdebug.remote_connect_back=1
" | sudo tee -a /etc/php/7.0/mods-available/xdebug.ini


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

# DB
# Set trust for postgres pg_hba.conf
sudo sed -i 's/local.*all.*postgres.*$/local    all             postgres                                trust/' /etc/postgresql/$PG_VERSION/main/pg_hba.conf
sudo sed -i 's/local.*all.*all.*$/local   all             all                                     trust/' /etc/postgresql/$PG_VERSION/main/pg_hba.conf
sudo sed -i 's/host.*all.*all.127.0.0.1\/32.*$/host    all             all             127.0.0.1\/32            trust/' /etc/postgresql/$PG_VERSION/main/pg_hba.conf
sudo sed -i 's/host.*all.*all.*::1\/128.*$/host    all             all             ::1\/128                 trust/' /etc/postgresql/$PG_VERSION/main/pg_hba.conf
# Restart postgresql
sudo service postgresql restart
sudo app/console doctrine:database:create
sudo app/console doctrine:migrations:migrate
sudo app/console doctrine:fixtures:load
