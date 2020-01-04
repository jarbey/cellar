# cellar
Project to manage cellar with raspberry

## Create RRD archive

rrdtool create maison.rrd \
--step '30' \
'DS:1_t:GAUGE:60:-20:60' \
'DS:1_h:GAUGE:60:0:100' \
'DS:2_t:GAUGE:60:-20:60' \
'DS:2_h:GAUGE:60:0:100' \
'DS:3_t:GAUGE:60:-20:60' \
'DS:3_h:GAUGE:60:0:100' \
'DS:4_t:GAUGE:60:-20:60' \
'DS:4_h:GAUGE:60:0:100' \
'RRA:MIN:0.99:1:480' \
'RRA:MIN:0.99:10:576' \
'RRA:MIN:0.99:120:672' \
'RRA:MIN:0.99:480:720' \
'RRA:MIN:0.99:2880:730' \
'RRA:MIN:0.99:20160:1043' \
'RRA:AVERAGE:0.99:1:480' \
'RRA:AVERAGE:0.99:10:576' \
'RRA:AVERAGE:0.99:120:672' \
'RRA:AVERAGE:0.99:480:720' \
'RRA:AVERAGE:0.99:2880:730' \
'RRA:AVERAGE:0.99:20160:1043' \
'RRA:MAX:0.99:1:480' \
'RRA:MAX:0.99:10:576' \
'RRA:MAX:0.99:120:672' \
'RRA:MAX:0.99:480:720' \
'RRA:MAX:0.99:2880:730' \
'RRA:MAX:0.99:20160:1043'

## INSTALL COMMAND

#### Install prerequisites
sudo apt-get update
sudo apt-get install git php-cli python-pip sqlite3 php-xml php-curl php-sqlite3
sudo python -m pip install --upgrade pip setuptools wheel
sudo pip install Adafruit_DHT

#### Install composer
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === 'baf1608c33254d00611ac1705c1d9958c817a1a33bce370c0595974b342601bd80b92a3f46067da89e3b06bff421f182') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"

#### Install cellar
git clone https://github.com/jarbey/cellar.git
cd cellar/
mv ../composer.phar ./
php composer.phar install --no-dev --optimize-autoloader

#### Adjust the .env file
change DB_ID

#### Copy Services ####
cp install/temperature*.service /etc/systemd/system/
sudo systemctl enable temperature_get
sudo systemctl enable temperature_send

#### Start Services ####
sudo systemctl start temperature_get
sudo systemctl start temperature_send


## UPDATE COMMAND
rm -r src/ ; git reset --hard origin/master ; git pull ; chmod 755 src/Python/*.py ; chmod 755 *.sh ; rm -r var/cache ; php composer.phar dumpautoload ; php bin/console cache:warmup
