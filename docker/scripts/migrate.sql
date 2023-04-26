CREATE TABLE qrcodestemp ( device_id bigint unsigned NOT NULL AUTO_INCREMENT, device_name varchar(255) DEFAULT NULL, device_details varchar(255) DEFAULT NULL, qrcode longblob NOT NULL, qrcode_action VARCHAR(255) DEFAULT NULL, PRIMARY KEY (device_id) ) ENGINE=myisam AUTO_INCREMENT=0 DEFAULT CHARSET=LATIN1;
INSERT INTO qrcodestemp (device_id,device_name,device_details,qrcode) SELECT * FROM qrcodes;
UPDATE qrcodestemp SET qrcode_action = 'email' WHERE 1=1;
DROP table qrcodes;
CREATE TABLE qrcodes ( device_id bigint unsigned NOT NULL AUTO_INCREMENT, device_name varchar(255) DEFAULT NULL, device_details varchar(255) DEFAULT NULL, qrcode longblob NOT NULL, qrcode_action VARCHAR(255) DEFAULT NULL, PRIMARY KEY (device_id) ) ENGINE=myisam AUTO_INCREMENT=0 DEFAULT CHARSET=LATIN1;
INSERT INTO qrcodes select * from qrcodestemp;
