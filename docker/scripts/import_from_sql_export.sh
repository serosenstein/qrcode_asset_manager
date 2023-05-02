#!/bin/bash
## if you have a mysql dump as an INSERT you can use this script to re-insert them into the system via the php scripts that should generate the QR code since null clobs are rejected
## mysql.back.sql is the name of the backup script used here, either rename your file to that or change the script
hostname=$1;
port=$2;
URI_base=$3;
if [ -z $hostname ] || [ -z $port ] || [ -z $URI_base ]
then
	echo "$0 <hostname> <port> <URI base>"
	exit 1;
fi

for line in $(cat mysql.back.sql | awk -F\( '{ print $3 }' | grep . | sed 's/, /,/g' | sed 's/);//g' | tr -d "'" | sed 's/ /%20/g')
do
	device_name=$(echo $line | awk -F, '{ print $1 }' | tr -d "'")
	device_details=$(echo $line | awk -F, '{ print $2 }' | tr -d "'" )
	echo "device name: $device_name"
	echo "device details: $device_details"
	curl "http://$hostname:$port/$URI_base/qrcodes_add.php" -X POST -H 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:109.0) Gecko/20100101 Firefox/112.0' -H 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8' -H 'Accept-Language: en-US,en;q=0.5' -H 'Accept-Encoding: gzip, deflate' -H 'Content-Type: application/x-www-form-urlencoded' -H "Origin: http://$hostname:$port" -H 'Connection: keep-alive' -H "Referer: http://$hostname/$URI_base" -H 'Upgrade-Insecure-Requests: 1' --data-raw "device_name=$device_name&device+details=$device_details&qrcode_action=email"

	
done
