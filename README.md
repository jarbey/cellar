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

## UPDATE COMMAND

rm -r src/ ; git reset --hard origin/master ; git pull ; chmod 755 src/Python/*.py ; chmod 755 *.sh ; rm -r var/cache ; php composer.phar dumpautoload ; php bin/console cache:clear
