#!/usr/bin/python

import sys
import time
import Adafruit_DHT
from Adafruit_DHT import Raspberry_Pi_2

def read(sensor, pin, retries=15):
    for i in range(retries):
        humidity, temperature = Raspberry_Pi_2.read(sensor, pin)
        if humidity is not None and temperature is not None:
            return (humidity, temperature)
        time.sleep(0.05)
    return (None, None)


# Parse command line parameters.
sensor_args = { '11': Adafruit_DHT.DHT11,
                '22': Adafruit_DHT.DHT22,
                '2302': Adafruit_DHT.AM2302 }

for x in range(1, len(sys.argv)):
    sensor, pin = sys.argv[x].split(',')
    humidity, temperature = read(int(sensor), int(pin))
    print('{0:0.1f};{1:0.1f}'.format(temperature, humidity))