# -= INSTALL =-
# sudo apt-get install i2c-tools
# sudo pip3 install adafruit-circuitpython-busdevice
# sudo pip3 install adafruit-circuitpython-tca9548a
# sudo pip3 install adafruit-circuitpython-sht31d
#
# - Then enable I2C
# sudo raspi-config
#

import time
import datetime
import board
import busio
import adafruit_tca9548a
import adafruit_sht31d
import requests
import sys
from requests.adapters import HTTPAdapter
from requests.packages.urllib3.util.retry import Retry
import traceback


session = requests.Session()
retry = Retry(connect=3, backoff_factor=0.5)
adapter = HTTPAdapter(max_retries=retry)
session.mount('http://', adapter)
session.mount('https://', adapter)


# Create I2C bus as normal
i2c = busio.I2C(board.SCL, board.SDA)

# Create the TCA9548A object and give it the I2C bus
tca = adafruit_tca9548a.TCA9548A(i2c)

sensor1 = adafruit_sht31d.SHT31D(tca[0], 0x45)
sensor2 = adafruit_sht31d.SHT31D(tca[1], 0x45)
sensor3 = adafruit_sht31d.SHT31D(tca[2], 0x45)
sensor4 = adafruit_sht31d.SHT31D(tca[2], 0x45)
sensor5 = adafruit_sht31d.SHT31D(tca[2], 0x45)

update_date = 0;

# Loop and profit!
while True:
    try:
        update_date = (int)(time.time())
        print('')
        print("Sensor1 - Temperature: %0.3f C" % sensor1.temperature)
        print("Sensor1 - Humidity: %0.3f %%" % sensor1.relative_humidity)
        print("Sensor2 - Temperature: %0.3f C" % sensor2.temperature)
        print("Sensor2 - Humidity: %0.3f %%" % sensor2.relative_humidity)
        print("Sensor3 - Temperature: %0.3f C" % sensor3.temperature)
        print("Sensor3 - Humidity: %0.3f %%" % sensor3.relative_humidity)
        print("Sensor4 - Temperature: %0.3f C" % sensor4.temperature)
        print("Sensor4 - Humidity: %0.3f %%" % sensor4.relative_humidity)
        print("Sensor5 - Temperature: %0.3f C" % sensor5.temperature)
        print("Sensor5 - Humidity: %0.3f %%" % sensor5.relative_humidity)

        put_data = {'date': datetime.datetime.now().strftime("%Y-%m-%dT%H:%M:%S+00:00"), 'sensor_data':[{'date': update_date, 'sensor':{'id':7}, 'temperature': sensor1.temperature, 'humidity': sensor1.relative_humidity}, {'date': update_date, 'sensor':{'id':8}, 'temperature': sensor2.temperature, 'humidity': sensor2.relative_humidity}, {'date': update_date, 'sensor':{'id':9}, 'temperature': sensor3.temperature, 'humidity': sensor3.relative_humidity}, {'date': update_date, 'sensor':{'id':10}, 'temperature': sensor4.temperature, 'humidity': sensor4.relative_humidity}, {'date': update_date, 'sensor':{'id':11}, 'temperature': sensor5.temperature, 'humidity': sensor5.relative_humidity}]};
        session.put('https://cellar.arbey.fr/api/3/' + str(update_date), json = put_data)
        time.sleep(10)
    except KeyboardInterrupt:
        # quit
        sys.exit()
    except:
        traceback.print_exc()
        time.sleep(3)
        pass