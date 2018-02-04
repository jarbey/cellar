#!/bin/sh

source .env

cd $DIR

while [ -z "$ip" ]
do
        sleep 1
        ip=`ifconfig | grep inet | grep -v inet6 | grep -v "127.0.0.1" | awk '{ print $2 }'`
done
$CELLAR_DIR/src/Python/exec_display.py "$ip" 2>&1 >> $CELLAR_DIR/display.log &

sleep 2

while true
do
    /usr/bin/php $CELLAR_DIR/bin/console cellar:information:update -vv 2>&1 >> $CELLAR_DIR/information_update.log &
    /usr/bin/php $CELLAR_DIR/bin/console cellar:data:send -vv 2>&1 >> $CELLAR_DIR/data_send.log &
sleep 15
done
