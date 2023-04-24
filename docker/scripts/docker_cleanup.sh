#!/bin/bash
docker ps -a | grep qrcode | awk '{ print $NF }' | while read pod;
do
docker stop $pod;
docker rm $pod;
done;
