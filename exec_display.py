#!/usr/bin/env python

import Image
import ImageDraw
import ImageFont

import Myway_ILI9486 as TFT
import Adafruit_GPIO as GPIO
import Adafruit_GPIO.SPI as SPI

import time
import socket
import threading
import json
import sys


fonts = {}
fonts[36] = ImageFont.truetype('/home/pi/cellar/arial.ttf', 36)
fonts[18] = ImageFont.truetype('/home/pi/cellar/arial.ttf', 18)

IP = sys.argv[1]

def draw_rotated_text(image, text, position, angle, font, fill=(255,255,255)):
    # Get rendered font width and height.
    draw = ImageDraw.Draw(image)
    width, height = draw.textsize(text, font=font)
    # Create a new image with transparent background to store the text.
    textimage = Image.new('RGBA', (width, height), (0,0,0,0))
    # Render the text.
    textdraw = ImageDraw.Draw(textimage)
    textdraw.text((0,0), text, font=font, fill=fill)
    # Rotate the text image.
    rotated = textimage.rotate(angle, expand=1)
    # Paste the text into the image, using it as a mask for transparency.
    image.paste(rotated, position, rotated)

def draw_lines(lines):
    disp.clear()
    i = 1
    for line in lines:
        # Check if font size already exists
        if line['font']['size'] not in fonts.keys():
            fonts[line['font']['size']] = ImageFont.truetype('/home/pi/cellar/arial.ttf', line['font']['size'])
        draw_rotated_text(disp.buffer, line['text'], (320 - line['position']['y'] - line['font']['size'], line['position']['x']), 90 + line['position']['angle'], fonts[line['font']['size']], fill=(line['color']['r'], line['color']['g'], line['color']['b']))
        i=i+1
    draw_rotated_text(disp.buffer, "IP : %s" % IP, (300, 180), 90, fonts[18], fill=(255,255,255))
    disp.display()

# Raspberry Pi configuration.
DC = 24
RST = 25
SPI_PORT = 0
SPI_DEVICE = 0


# Create TFT LCD display class.
disp = TFT.ILI9486(DC, rst=RST, spi=SPI.SpiDev(SPI_PORT, SPI_DEVICE, max_speed_hz=64000000))


# Initialize display.
disp.begin()



class ClientThread(threading.Thread):

    def __init__(self, ip, port, clientsocket):
        threading.Thread.__init__(self)
        self.ip = ip
        self.port = port
        self.clientsocket = clientsocket

    def run(self):
        while True:
            r = self.clientsocket.recv(8192)

            try:
                print("Recu : %s" % r)
                data = json.loads(r)
            except:
                print "ERROR PARSING JSON"
                sys.exit(1)

            draw_lines(data['display'])
            self.clientsocket.send("1")

tcpsock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
tcpsock.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)
tcpsock.bind(("",1111))

draw_lines([])

while True:
    tcpsock.listen(10)
    print( "En ecoute...")
    (clientsocket, (ip, port)) = tcpsock.accept()
    newthread = ClientThread(ip, port, clientsocket)
    newthread.start()


