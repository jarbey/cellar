#!/bin/sh

BASEDIR=$(dirname "$0")
echo "$BASEDIR"

export $(cat $BASEDIR/.env | grep -v ^# | xargs)
cd $CELLAR_DIR

/usr/bin/php $CELLAR_DIR/bin/console cellar:temperature:send -vv >> $CELLAR_DIR/temperature_send.log 2>&1
