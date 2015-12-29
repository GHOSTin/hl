## dependencies

- php5
- php5-cli
- php5-fpm
- php5-json
- php5-mysql
- apache2

`sudo apt-get install php5 php5-cli php5-fpm php5-json php5-mysql`
`sudo apt-get install apache2`
`sudo apt-get install mysql-server`

all
`sudo apt-get install php5 php5-cli php5-fpm php5-json php5-mysql apache2 mysql-server`


## setting
sudo a2enmod proxy_fcgi rewrite


## apache2 config
<VirtualHost *:80>
        ServerAdmin nekrasov@mlsco.ru
        ServerName main.mshc.ru
        ServerAlias www.main.mshc.ru
        DocumentRoot /home/ubuntu/mshc/main/site

        <Directory /home/ubuntu/mshc/main/site/>
                Options Indexes FollowSymLinks MultiViews
                AllowOverride All
                Order allow,deny
                allow from all
                Require all granted
        </Directory>

        ProxyPass /index.php fcgi://localhost:50000/home/ubuntu/mshc/main/site/index.php
        ErrorLog ${APACHE_LOG_DIR}/e_mshc_main.log
</VirtualHost>

<VirtualHost *:80>
        ServerAdmin nekrasov@mlsco.ru
        ServerName client.mshc.ru
        ServerAlias www.client.mshc.ru
        DocumentRoot /home/ubuntu/mshc/client/site

        <Directory /home/ubuntu/mshc/client/site/>
                Options Indexes FollowSymLinks MultiViews
                AllowOverride All
                Order allow,deny
                allow from all
                Require all granted
        </Directory>

        ProxyPass /index.php fcgi://localhost:50000/home/ubuntu/mshc/client/site/index.php
        ErrorLog ${APACHE_LOG_DIR}/e_mshc_client.log
</VirtualHost>



sudo service apache2 restart
