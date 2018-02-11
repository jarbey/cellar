#!/bin/sh

BASEDIR=$(dirname "$0")
echo "$BASEDIR"

export $(cat $BASEDIR/.env | grep -v ^# | xargs)
cd $CELLAR_DIR

/usr/bin/php $CELLAR_DIR/bin/console cellar:websocket:server -vv 2>&1 >> $CELLAR_DIR/websocket.log
