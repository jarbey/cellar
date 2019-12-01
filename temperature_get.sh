#!/bin/sh

BASEDIR=$(dirname "$0")
echo "$BASEDIR"

export $(cat $BASEDIR/.env | grep -v ^# | xargs)
cd $CELLAR_DIR

/usr/bin/php $CELLAR_DIR/bin/console cellar:information:update -vv >> $CELLAR_DIR/temperature_get.log 2>&1