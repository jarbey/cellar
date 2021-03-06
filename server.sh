#!/bin/sh

BASEDIR=$(dirname "$0")
echo "$BASEDIR"

export $(cat $BASEDIR/.env | grep -v ^# | xargs)
cd $CELLAR_DIR

/usr/bin/php $CELLAR_DIR/bin/console cellar:temperature:websocket:server -vv >> $CELLAR_DIR/websocket.log 2>&1
