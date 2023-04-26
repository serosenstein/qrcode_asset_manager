CREATE TABLE qrcodes ( device_id bigint unsigned NOT NULL AUTO_INCREMENT, device_name varchar(255) DEFAULT NULL, device_details varchar(255) DEFAULT NULL, qrcode longblob NOT NULL, qrcode_action VARCHAR(255) DEFAULT NULL, PRIMARY KEY (device_id) ) ENGINE=myisam AUTO_INCREMENT=0 DEFAULT CHARSET=LATIN1;
commit;
