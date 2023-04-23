CREATE TABLE qrcodes ( device_id bigint unsigned NOT NULL AUTO_INCREMENT, device_name varchar(255) DEFAULT NULL, device_details varchar(255) DEFAULT NULL, qrcode longblob NOT NULL, PRIMARY KEY (device_id) ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;
commit;
