#!/bin/bash
hostname=$1;
port=$2;
URI_base=$3;
device_id=$4;
if [ -z $hostname ] || [ -z $port ] || [ -z $URI_base ] || [ -z $device_id ]
then
	echo "$0 <hostname> <port> <URI base> <device_id>"
	exit 1;
fi

	device_name=$(echo $line | awk -F, '{ print $1 }' | tr -d "'")
	device_details=$(echo $line | awk -F, '{ print $2 }' | tr -d "'" )
	echo "device name: $device_name"
	echo "device details: $device_details"
	curl "http://$hostname:$port/$URI_base/qrcodes_regenerate.php" -X POST -H 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:109.0) Gecko/20100101 Firefox/112.0' -H 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8' -H 'Accept-Language: en-US,en;q=0.5' -H 'Accept-Encoding: gzip, deflate' -H 'Content-Type: application/x-www-form-urlencoded' -H "Origin: http://$hostname:$port" -H 'Connection: keep-alive' -H "Referer: http://$hostname/$URI_base" -H 'Upgrade-Insecure-Requests: 1' --data-raw "device_id=$device_id"
