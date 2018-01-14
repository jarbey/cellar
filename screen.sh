#!/bin/sh

while [ -z "$ip" ]
do
        sleep 1
        ip=`ifconfig | grep inet | grep -v inet6 | grep -v "127.0.0.1" | awk '{ print $2 }'`
done
/home/pi/cellar/exec_display.py "$ip" &

/usr/bin/php /home/pi/cellar/bin/console bin/console cellar:information:update