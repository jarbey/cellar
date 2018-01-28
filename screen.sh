#!/bin/sh

while [ -z "$ip" ]
do
        sleep 1
        ip=`ifconfig | grep inet | grep -v inet6 | grep -v "127.0.0.1" | awk '{ print $2 }'`
done
/home/pi/cellar/exec_display.py "$ip" 2>&1 >> /home/pi/cellar/display.log &

sleep 2

while true
do
    /usr/bin/php /home/pi/cellar/bin/console cellar:information:update -vv 2>&1 >> /home/pi/cellar/information_update.log &
    /usr/bin/php /home/pi/cellar/bin/console cellar:data:send -vv 2>&1 >> /home/pi/cellar/data_send.log &
sleep 5
done
