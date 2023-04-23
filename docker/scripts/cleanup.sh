#!/bin/bash
podman ps -a | grep qrcode | awk '{ print $NF }' | while read pod;
do
podman stop $pod;
podman rm $pod;
done;
